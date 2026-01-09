<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BorrowingModel;
use App\Models\BookModel;
use App\Models\MemberModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

/**
 * BorrowingController.php
 * Controller untuk peminjaman, pengembalian, dan history
 * 
 * Project: Kataraksa - Sistem Perpustakaan Digital
 * Author: Dimas
 */
class BorrowingController extends BaseController
{
    protected BorrowingModel $borrowingModel;
    protected BookModel $bookModel;
    protected MemberModel $memberModel;

    public function __construct()
    {
        $this->borrowingModel = new BorrowingModel();
        $this->bookModel = new BookModel();
        $this->memberModel = new MemberModel();
    }

    /**
     * List semua peminjaman (filter: semua, aktif, selesai, terlambat)
     *
     * @return string
     */
    public function index(): string
    {
        $filter = $this->request->getGet('filter') ?? 'all';
        $bookIdFromUrl = $this->request->getGet('book_id');

        // Get borrowings based on filter
        switch ($filter) {
            case 'active':
            case 'borrowed':
                $borrowings = $this->borrowingModel->getBorrowingsByStatus('borrowed');
                break;
            case 'returned':
                $borrowings = $this->borrowingModel->getBorrowingsByStatus('returned');
                break;
            case 'overdue':
                $borrowings = $this->borrowingModel->getBorrowingsByStatus('overdue');
                break;
            default:
                $borrowings = $this->borrowingModel->getBorrowingsWithDetails();
                $filter = 'all';
                break;
        }

        // Add overdue days calculation for each borrowing
        foreach ($borrowings as &$borrowing) {
            $borrowing['overdue_days'] = $this->calculateOverdueDays($borrowing);
            $borrowing['is_overdue'] = $this->isOverdue($borrowing);
        }

        // Get selected book if book_id provided
        $selectedBook = null;
        if ($bookIdFromUrl) {
            $selectedBook = $this->bookModel->find($bookIdFromUrl);
        }

        $data = [
            'title'        => 'Manajemen Peminjaman',
            'borrowings'   => $borrowings,
            'filter'       => $filter,
            'stats'        => $this->borrowingModel->getBorrowingsCountByStatus(),
            'members'      => $this->memberModel->getActiveMembers(),
            'books'        => $this->bookModel->getAvailableBooks(),
            'selectedBook' => $selectedBook,
            'openModal'    => $bookIdFromUrl ? true : false,
        ];

        return view('admin/borrowings/index', $data);
    }

    /**
     * Form peminjaman (pilih anggota, pilih buku yang available)
     *
     * @return string
     */
    public function create(): string
    {
        $data = [
            'title'      => 'Tambah Peminjaman',
            'members'    => $this->memberModel->getActiveMembers(),
            'books'      => $this->bookModel->getAvailableBooks(),
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/borrowings/create', $data);
    }

    /**
     * Simpan peminjaman:
     * - Validasi stok buku > 0
     * - Set borrow_date = today
     * - Set due_date = today + 7 days
     * - Set status = 'borrowed'
     * - Kurangi available di tabel books
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store()
    {
        // Validation rules
        $rules = [
            'member_id' => 'required|integer|is_not_unique[members.id]',
            'book_id'   => 'required|integer|is_not_unique[books.id]',
            'notes'     => 'permit_empty',
        ];

        $messages = [
            'member_id' => [
                'required'      => 'Anggota harus dipilih.',
                'is_not_unique' => 'Anggota tidak ditemukan.',
            ],
            'book_id' => [
                'required'      => 'Buku harus dipilih.',
                'is_not_unique' => 'Buku tidak ditemukan.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $bookId = (int) $this->request->getPost('book_id');
        $memberId = (int) $this->request->getPost('member_id');

        // Check book availability
        $book = $this->bookModel->find($bookId);
        if (!$book) {
            return redirect()->back()->withInput()->with('error', 'Buku tidak ditemukan.');
        }

        if ($book['available'] <= 0) {
            return redirect()->back()->withInput()->with('error', 'Stok buku tidak tersedia.');
        }

        // Check member exists
        $member = $this->memberModel->find($memberId);
        if (!$member) {
            return redirect()->back()->withInput()->with('error', 'Anggota tidak ditemukan.');
        }

        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Prepare borrowing data
            $borrowDate = date('Y-m-d');
            $dueDate = date('Y-m-d', strtotime('+7 days'));

            $borrowingData = [
                'member_id'   => $memberId,
                'book_id'     => $bookId,
                'borrow_date' => $borrowDate,
                'due_date'    => $dueDate,
                'return_date' => null,
                'status'      => 'borrowed',
                'notes'       => $this->request->getPost('notes'),
            ];

            // Insert borrowing record
            $this->borrowingModel->insert($borrowingData);

            // Decrease book available stock
            $this->bookModel->decreaseAvailable($bookId);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new DatabaseException('Transaction failed');
            }

            return redirect()->to('/admin/borrowings')->with('success', 'Peminjaman berhasil dicatat.');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Borrowing store error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat peminjaman. Silakan coba lagi.');
        }
    }

    /**
     * Proses pengembalian:
     * - Set return_date = today
     * - Cek keterlambatan (return_date > due_date ? 'overdue' : 'returned')
     * - Tambah available di tabel books
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function returnBook(int $id)
    {
        $borrowing = $this->borrowingModel->find($id);

        if (!$borrowing) {
            return redirect()->to('/admin/borrowings')->with('error', 'Data peminjaman tidak ditemukan.');
        }

        // Check if already returned
        if ($borrowing['status'] !== 'borrowed') {
            return redirect()->to('/admin/borrowings')->with('error', 'Buku sudah dikembalikan sebelumnya.');
        }

        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $returnDate = date('Y-m-d');
            $dueDate = $borrowing['due_date'];

            // Determine status based on return date vs due date
            $status = (strtotime($returnDate) > strtotime($dueDate)) ? 'overdue' : 'returned';

            // Update borrowing record
            $this->borrowingModel->update($id, [
                'return_date' => $returnDate,
                'status'      => $status,
            ]);

            // Increase book available stock
            $this->bookModel->increaseAvailable($borrowing['book_id']);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new DatabaseException('Transaction failed');
            }

            $message = 'Buku berhasil dikembalikan.';
            if ($status === 'overdue') {
                $overdueDays = $this->calculateOverdueDaysFromDates($returnDate, $dueDate);
                $message .= " (Terlambat {$overdueDays} hari)";
            }

            return redirect()->to('/admin/borrowings')->with('success', $message);

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Return book error: ' . $e->getMessage());
            return redirect()->to('/admin/borrowings')->with('error', 'Gagal memproses pengembalian. Silakan coba lagi.');
        }
    }

    /**
     * History semua peminjaman dengan filter
     *
     * @return string
     */
    public function history(): string
    {
        $filter = $this->request->getGet('filter') ?? 'all';
        $memberId = $this->request->getGet('member_id');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        // Build query
        $builder = $this->borrowingModel
            ->select('borrowings.*, members.name as member_name, members.email as member_email, books.title as book_title, books.author as book_author')
            ->join('members', 'members.id = borrowings.member_id', 'left')
            ->join('books', 'books.id = borrowings.book_id', 'left');

        // Apply filters
        if ($filter === 'borrowed' || $filter === 'active') {
            $builder->where('borrowings.status', 'borrowed');
        } elseif ($filter === 'returned') {
            $builder->where('borrowings.status', 'returned');
        } elseif ($filter === 'overdue') {
            $builder->groupStart()
                    ->where('borrowings.status', 'overdue')
                    ->orGroupStart()
                        ->where('borrowings.status', 'borrowed')
                        ->where('borrowings.due_date <', date('Y-m-d'))
                    ->groupEnd()
                    ->groupEnd();
        }

        // Filter by member
        if ($memberId) {
            $builder->where('borrowings.member_id', $memberId);
        }

        // Filter by date range
        if ($startDate) {
            $builder->where('borrowings.borrow_date >=', $startDate);
        }
        if ($endDate) {
            $builder->where('borrowings.borrow_date <=', $endDate);
        }

        $borrowings = $builder->orderBy('borrowings.created_at', 'DESC')->findAll();

        // Add overdue days calculation
        foreach ($borrowings as &$borrowing) {
            $borrowing['overdue_days'] = $this->calculateOverdueDays($borrowing);
            $borrowing['is_overdue'] = $this->isOverdue($borrowing);
        }

        $data = [
            'title'      => 'History Peminjaman',
            'borrowings' => $borrowings,
            'filter'     => $filter,
            'members'    => $this->memberModel->findAll(),
            'filters'    => [
                'member_id'  => $memberId,
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ],
            'stats'      => $this->borrowingModel->getBorrowingsCountByStatus(),
        ];

        return view('admin/borrowings/history', $data);
    }

    /**
     * Show borrowing detail
     *
     * @param int $id
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function show(int $id)
    {
        $borrowing = $this->borrowingModel->getBorrowingWithDetails($id);

        if (!$borrowing) {
            return redirect()->to('/admin/borrowings')->with('error', 'Data peminjaman tidak ditemukan.');
        }

        // Add overdue calculation
        $borrowing['overdue_days'] = $this->calculateOverdueDays($borrowing);
        $borrowing['is_overdue'] = $this->isOverdue($borrowing);

        $data = [
            'title'     => 'Detail Peminjaman',
            'borrowing' => $borrowing,
        ];

        return view('admin/borrowings/show', $data);
    }

    /**
     * Delete borrowing record (only if returned)
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete(int $id)
    {
        $borrowing = $this->borrowingModel->find($id);

        if (!$borrowing) {
            return redirect()->to('/admin/borrowings')->with('error', 'Data peminjaman tidak ditemukan.');
        }

        // Only allow deletion of returned borrowings
        if ($borrowing['status'] === 'borrowed') {
            return redirect()->to('/admin/borrowings')->with('error', 'Tidak dapat menghapus peminjaman yang masih aktif.');
        }

        if ($this->borrowingModel->delete($id)) {
            return redirect()->to('/admin/borrowings')->with('success', 'Data peminjaman berhasil dihapus.');
        }

        return redirect()->to('/admin/borrowings')->with('error', 'Gagal menghapus data peminjaman.');
    }

    /**
     * Calculate overdue days for a borrowing
     *
     * @param array $borrowing
     * @return int
     */
    private function calculateOverdueDays(array $borrowing): int
    {
        $dueDate = strtotime($borrowing['due_date']);
        
        // If returned, calculate from return date
        if ($borrowing['return_date']) {
            $returnDate = strtotime($borrowing['return_date']);
            if ($returnDate > $dueDate) {
                return (int) ceil(($returnDate - $dueDate) / 86400);
            }
            return 0;
        }

        // If not returned, calculate from today
        $today = strtotime(date('Y-m-d'));
        if ($today > $dueDate) {
            return (int) ceil(($today - $dueDate) / 86400);
        }

        return 0;
    }

    /**
     * Calculate overdue days from two dates
     *
     * @param string $returnDate
     * @param string $dueDate
     * @return int
     */
    private function calculateOverdueDaysFromDates(string $returnDate, string $dueDate): int
    {
        $return = strtotime($returnDate);
        $due = strtotime($dueDate);
        
        if ($return > $due) {
            return (int) ceil(($return - $due) / 86400);
        }
        
        return 0;
    }

    /**
     * Check if borrowing is overdue
     *
     * @param array $borrowing
     * @return bool
     */
    private function isOverdue(array $borrowing): bool
    {
        // Already marked as overdue
        if ($borrowing['status'] === 'overdue') {
            return true;
        }

        // If still borrowed and past due date
        if ($borrowing['status'] === 'borrowed') {
            $dueDate = strtotime($borrowing['due_date']);
            $today = strtotime(date('Y-m-d'));
            return $today > $dueDate;
        }

        return false;
    }

    /**
     * Extend due date for a borrowing
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function extend(int $id)
    {
        $borrowing = $this->borrowingModel->find($id);

        if (!$borrowing) {
            return redirect()->to('/admin/borrowings')->with('error', 'Data peminjaman tidak ditemukan.');
        }

        if ($borrowing['status'] !== 'borrowed') {
            return redirect()->to('/admin/borrowings')->with('error', 'Hanya peminjaman aktif yang dapat diperpanjang.');
        }

        // Extend due date by 7 days from current due date
        $newDueDate = date('Y-m-d', strtotime($borrowing['due_date'] . ' +7 days'));

        if ($this->borrowingModel->update($id, ['due_date' => $newDueDate])) {
            return redirect()->to('/admin/borrowings')->with('success', 'Masa peminjaman berhasil diperpanjang hingga ' . date('d/m/Y', strtotime($newDueDate)));
        }

        return redirect()->to('/admin/borrowings')->with('error', 'Gagal memperpanjang masa peminjaman.');
    }
}
