<?php
/**
 * Dashboard.php
 * Controller untuk dashboard member
 * 
 * Project: Kataraksa - Sistem Perpustakaan Digital
 * Author: Dimas
 */

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\BorrowingModel;
use App\Models\MemberModel;
use App\Models\BookModel;

class Dashboard extends BaseController
{
    protected $borrowingModel;
    protected $memberModel;
    protected $bookModel;

    public function __construct()
    {
        $this->borrowingModel = new BorrowingModel();
        $this->memberModel = new MemberModel();
        $this->bookModel = new BookModel();
    }

    /**
     * Dashboard utama member
     */
    public function index()
    {
        $memberId = session()->get('member_id');
        
        // Get member's borrowings
        $borrowings = $this->borrowingModel
            ->select('borrowings.*, books.title as book_title, books.author as book_author, books.cover')
            ->join('books', 'books.id = borrowings.book_id')
            ->where('borrowings.member_id', $memberId)
            ->orderBy('borrowings.created_at', 'DESC')
            ->findAll();
        
        $activeBorrowings = array_filter($borrowings, fn($b) => $b['status'] === 'borrowed');
        $overdueBorrowings = array_filter($borrowings, fn($b) => $b['status'] === 'overdue' || ($b['status'] === 'borrowed' && strtotime($b['due_date']) < time()));
        
        return view('member/dashboard', [
            'title' => 'Dashboard Member - Kataraksa',
            'pageTitle' => 'Dashboard',
            'borrowings' => $borrowings,
            'activeBorrowings' => count($activeBorrowings),
            'overdueBorrowings' => count($overdueBorrowings),
            'totalBorrowings' => count($borrowings),
        ]);
    }

    /**
     * Halaman daftar peminjaman member
     */
    public function borrowings()
    {
        $memberId = session()->get('member_id');
        
        // Get all borrowings for this member
        $borrowings = $this->borrowingModel
            ->select('borrowings.*, books.title as book_title, books.author as book_author, books.cover')
            ->join('books', 'books.id = borrowings.book_id')
            ->where('borrowings.member_id', $memberId)
            ->orderBy('borrowings.created_at', 'DESC')
            ->findAll();
        
        return view('member/borrowings', [
            'title' => 'Peminjaman Saya - Kataraksa',
            'pageTitle' => 'Peminjaman Saya',
            'borrowings' => $borrowings,
        ]);
    }

    /**
     * Halaman katalog buku untuk member
     */
    public function catalog()
    {
        $keyword = $this->request->getGet('search');
        $categoryId = $this->request->getGet('category');
        
        $categoryModel = new \App\Models\CategoryModel();
        $categories = $categoryModel->findAll();
        
        // Build query
        $builder = $this->bookModel
            ->select('books.*, categories.name as category_name')
            ->join('categories', 'categories.id = books.category_id', 'left');
        
        if ($keyword) {
            $builder->groupStart()
                    ->like('books.title', $keyword)
                    ->orLike('books.author', $keyword)
                    ->orLike('books.isbn', $keyword)
                    ->groupEnd();
        }
        
        if ($categoryId) {
            $builder->where('books.category_id', $categoryId);
        }
        
        $books = $builder->orderBy('books.title', 'ASC')->findAll();
        
        return view('member/catalog', [
            'title' => 'Katalog Buku - Kataraksa',
            'pageTitle' => 'Katalog Buku',
            'books' => $books,
            'categories' => $categories,
            'keyword' => $keyword,
            'selectedCategory' => $categoryId,
        ]);
    }

    /**
     * Proses peminjaman buku oleh member
     */
    public function borrow($bookId)
    {
        $book = $this->bookModel->find($bookId);
        
        if (!$book) {
            return redirect()->back()->with('error', 'Buku tidak ditemukan.');
        }
        
        if ($book['available'] <= 0) {
            return redirect()->back()->with('error', 'Buku tidak tersedia untuk dipinjam.');
        }
        
        $memberId = session()->get('member_id');
        
        // Check if already borrowing this book
        $existing = $this->borrowingModel
            ->where('member_id', $memberId)
            ->where('book_id', $bookId)
            ->where('status', 'borrowed')
            ->first();
        
        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah meminjam buku ini.');
        }
        
        // Check max borrowing limit (e.g., 3 books)
        $activeBorrowings = $this->borrowingModel
            ->where('member_id', $memberId)
            ->where('status', 'borrowed')
            ->countAllResults();
        
        if ($activeBorrowings >= 3) {
            return redirect()->back()->with('error', 'Anda sudah mencapai batas maksimal peminjaman (3 buku).');
        }
        
        // Check if member has overdue books
        $overdueCount = $this->borrowingModel
            ->where('member_id', $memberId)
            ->groupStart()
                ->where('status', 'overdue')
                ->orGroupStart()
                    ->where('status', 'borrowed')
                    ->where('due_date <', date('Y-m-d'))
                ->groupEnd()
            ->groupEnd()
            ->countAllResults();
        
        if ($overdueCount > 0) {
            return redirect()->back()->with('error', 'Anda memiliki buku yang terlambat dikembalikan. Silakan kembalikan terlebih dahulu.');
        }
        
        $db = \Config\Database::connect();
        $db->transStart();
        
        $this->borrowingModel->insert([
            'member_id' => $memberId,
            'book_id' => $bookId,
            'borrow_date' => date('Y-m-d'),
            'due_date' => date('Y-m-d', strtotime('+7 days')),
            'status' => 'borrowed',
        ]);
        
        $this->bookModel->decreaseAvailable($bookId);
        
        $db->transComplete();
        
        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memproses peminjaman. Silakan coba lagi.');
        }
        
        return redirect()->to('/member/dashboard')->with('swal_success', 'Buku "' . esc($book['title']) . '" berhasil dipinjam! Jatuh tempo: ' . date('d M Y', strtotime('+7 days')));
    }
}
