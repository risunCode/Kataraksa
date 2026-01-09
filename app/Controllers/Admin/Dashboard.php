<?php
/**
 * Dashboard.php
 * Controller untuk statistik dashboard admin
 * 
 * Project: Kataraksa - Sistem Perpustakaan Digital
 * Author: Dimas
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BookModel;
use App\Models\MemberModel;
use App\Models\BorrowingModel;

class Dashboard extends BaseController
{
    protected $bookModel;
    protected $memberModel;
    protected $borrowingModel;

    public function __construct()
    {
        $this->bookModel = new BookModel();
        $this->memberModel = new MemberModel();
        $this->borrowingModel = new BorrowingModel();
    }

    /**
     * Tampilkan dashboard dengan statistik
     * - Total buku
     * - Total anggota
     * - Peminjaman aktif (status = borrowed)
     * - Peminjaman terlambat (status = overdue atau due_date < today)
     * 
     * @return string
     */
    public function index()
    {
        // Total buku
        $totalBooks = $this->bookModel->countAllResults();

        // Total anggota
        $totalMembers = $this->memberModel->countAllResults();

        // Peminjaman aktif (status = borrowed)
        $activeBorrowings = $this->borrowingModel
            ->where('status', 'borrowed')
            ->countAllResults();

        // Peminjaman terlambat
        // Status = overdue ATAU (status = borrowed DAN due_date < today)
        $today = date('Y-m-d');
        
        $overdueBorrowings = $this->borrowingModel
            ->groupStart()
                ->where('status', 'overdue')
                ->orGroupStart()
                    ->where('status', 'borrowed')
                    ->where('due_date <', $today)
                ->groupEnd()
            ->groupEnd()
            ->countAllResults();

        // Peminjaman terbaru (5 terakhir)
        $recentBorrowings = $this->borrowingModel->getRecentBorrowings(5);

        // Buku populer (paling sering dipinjam)
        $popularBooks = $this->borrowingModel->getPopularBooks(5);

        $data = [
            'title'             => 'Dashboard - Kataraksa',
            'totalBooks'        => $totalBooks,
            'totalMembers'      => $totalMembers,
            'activeBorrowings'  => $activeBorrowings,
            'overdueBorrowings' => $overdueBorrowings,
            'recentBorrowings'  => $recentBorrowings,
            'popularBooks'      => $popularBooks,
        ];

        return view('admin/dashboard', $data);
    }
}
