<?php
/**
 * BorrowingModel.php
 * Model untuk transaksi peminjaman
 * 
 * Project: Kataraksa - Sistem Perpustakaan Digital
 * Author: Dimas
 */

namespace App\Models;

use CodeIgniter\Model;

class BorrowingModel extends Model
{
    protected $table            = 'borrowings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'member_id',
        'book_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
        'notes'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'member_id'   => 'required|integer',
        'book_id'     => 'required|integer',
        'borrow_date' => 'required|valid_date',
        'due_date'    => 'required|valid_date',
        'return_date' => 'permit_empty|valid_date',
        'status'      => 'required|in_list[borrowed,returned,overdue]',
        'notes'       => 'permit_empty',
    ];

    protected $validationMessages = [
        'member_id' => [
            'required' => 'Anggota harus dipilih.',
            'integer'  => 'Anggota tidak valid.',
        ],
        'book_id' => [
            'required' => 'Buku harus dipilih.',
            'integer'  => 'Buku tidak valid.',
        ],
        'borrow_date' => [
            'required'   => 'Tanggal pinjam harus diisi.',
            'valid_date' => 'Format tanggal pinjam tidak valid.',
        ],
        'due_date' => [
            'required'   => 'Tanggal jatuh tempo harus diisi.',
            'valid_date' => 'Format tanggal jatuh tempo tidak valid.',
        ],
        'return_date' => [
            'valid_date' => 'Format tanggal kembali tidak valid.',
        ],
        'status' => [
            'required' => 'Status harus dipilih.',
            'in_list'  => 'Status tidak valid.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Get all borrowings with member and book details
     *
     * @return array
     */
    public function getBorrowingsWithDetails(): array
    {
        return $this->select('borrowings.*, members.name as member_name, books.title as book_title')
                    ->join('members', 'members.id = borrowings.member_id', 'left')
                    ->join('books', 'books.id = borrowings.book_id', 'left')
                    ->orderBy('borrowings.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get borrowing by ID with member and book details
     *
     * @param int $id
     * @return array|null
     */
    public function getBorrowingWithDetails(int $id): ?array
    {
        return $this->select('borrowings.*, members.name as member_name, members.email as member_email, books.title as book_title, books.author as book_author')
                    ->join('members', 'members.id = borrowings.member_id', 'left')
                    ->join('books', 'books.id = borrowings.book_id', 'left')
                    ->where('borrowings.id', $id)
                    ->first();
    }

    /**
     * Get active borrowings (status = borrowed)
     *
     * @return array
     */
    public function getActiveBorrowings(): array
    {
        return $this->select('borrowings.*, members.name as member_name, books.title as book_title')
                    ->join('members', 'members.id = borrowings.member_id', 'left')
                    ->join('books', 'books.id = borrowings.book_id', 'left')
                    ->where('borrowings.status', 'borrowed')
                    ->orderBy('borrowings.due_date', 'ASC')
                    ->findAll();
    }

    /**
     * Get overdue borrowings
     * Status = overdue OR (status = borrowed AND due_date < today)
     *
     * @return array
     */
    public function getOverdueBorrowings(): array
    {
        $today = date('Y-m-d');
        
        return $this->select('borrowings.*, members.name as member_name, books.title as book_title')
                    ->join('members', 'members.id = borrowings.member_id', 'left')
                    ->join('books', 'books.id = borrowings.book_id', 'left')
                    ->groupStart()
                        ->where('borrowings.status', 'overdue')
                        ->orGroupStart()
                            ->where('borrowings.status', 'borrowed')
                            ->where('borrowings.due_date <', $today)
                        ->groupEnd()
                    ->groupEnd()
                    ->orderBy('borrowings.due_date', 'ASC')
                    ->findAll();
    }

    /**
     * Get borrowings by member
     *
     * @param int $memberId
     * @return array
     */
    public function getBorrowingsByMember(int $memberId): array
    {
        return $this->select('borrowings.*, books.title as book_title, books.author as book_author')
                    ->join('books', 'books.id = borrowings.book_id', 'left')
                    ->where('borrowings.member_id', $memberId)
                    ->orderBy('borrowings.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get recent borrowings
     *
     * @param int $limit
     * @return array
     */
    public function getRecentBorrowings(int $limit = 5): array
    {
        return $this->select('borrowings.*, members.name as member_name, books.title as book_title')
                    ->join('members', 'members.id = borrowings.member_id', 'left')
                    ->join('books', 'books.id = borrowings.book_id', 'left')
                    ->orderBy('borrowings.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get popular books (most borrowed)
     *
     * @param int $limit
     * @return array
     */
    public function getPopularBooks(int $limit = 5): array
    {
        return $this->select('books.id, books.title, books.author, books.cover, COUNT(borrowings.id) as borrow_count')
                    ->join('books', 'books.id = borrowings.book_id', 'left')
                    ->groupBy('borrowings.book_id')
                    ->orderBy('borrow_count', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Check if member has active borrowing for a specific book
     *
     * @param int $memberId
     * @param int $bookId
     * @return bool
     */
    public function hasActiveBorrowing(int $memberId, int $bookId): bool
    {
        $count = $this->where('member_id', $memberId)
                      ->where('book_id', $bookId)
                      ->where('status', 'borrowed')
                      ->countAllResults();
        
        return $count > 0;
    }

    /**
     * Calculate days overdue
     *
     * @param string $dueDate
     * @param string|null $returnDate
     * @return int
     */
    public function calculateDaysOverdue(string $dueDate, ?string $returnDate = null): int
    {
        $due = strtotime($dueDate);
        $return = $returnDate ? strtotime($returnDate) : time();
        
        $diff = $return - $due;
        $days = floor($diff / (60 * 60 * 24));
        
        return max(0, $days);
    }

    /**
     * Update overdue status for all borrowed items past due date
     *
     * @return int Number of updated records
     */
    public function updateOverdueStatus(): int
    {
        $today = date('Y-m-d');
        
        return $this->where('status', 'borrowed')
                    ->where('due_date <', $today)
                    ->set(['status' => 'overdue'])
                    ->update();
    }

    /**
     * Get borrowings by status with member and book details
     *
     * @param string $status
     * @return array
     */
    public function getBorrowingsByStatus(string $status): array
    {
        $builder = $this->select('borrowings.*, members.name as member_name, books.title as book_title')
                        ->join('members', 'members.id = borrowings.member_id', 'left')
                        ->join('books', 'books.id = borrowings.book_id', 'left');

        if ($status === 'overdue') {
            $today = date('Y-m-d');
            $builder->groupStart()
                    ->where('borrowings.status', 'overdue')
                    ->orGroupStart()
                        ->where('borrowings.status', 'borrowed')
                        ->where('borrowings.due_date <', $today)
                    ->groupEnd()
                    ->groupEnd();
        } else {
            $builder->where('borrowings.status', $status);
        }

        return $builder->orderBy('borrowings.created_at', 'DESC')->findAll();
    }

    /**
     * Get count of borrowings grouped by status
     *
     * @return array
     */
    public function getBorrowingsCountByStatus(): array
    {
        $today = date('Y-m-d');
        
        $total = $this->countAllResults(false);
        $borrowed = $this->where('status', 'borrowed')->countAllResults(false);
        $returned = $this->where('status', 'returned')->countAllResults(false);
        
        // Overdue includes status='overdue' OR (status='borrowed' AND due_date < today)
        $overdue = $this->groupStart()
                        ->where('status', 'overdue')
                        ->orGroupStart()
                            ->where('status', 'borrowed')
                            ->where('due_date <', $today)
                        ->groupEnd()
                        ->groupEnd()
                        ->countAllResults(false);

        return [
            'total'    => $total,
            'borrowed' => $borrowed,
            'returned' => $returned,
            'overdue'  => $overdue,
        ];
    }

    /**
     * Check if member has any active borrowings
     *
     * @param int $memberId
     * @return bool
     */
    public function memberHasActiveBorrowings(int $memberId): bool
    {
        $count = $this->where('member_id', $memberId)
                      ->whereIn('status', ['borrowed', 'overdue'])
                      ->countAllResults();
        
        return $count > 0;
    }
}
