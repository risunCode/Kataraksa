<?php

namespace App\Controllers;

use App\Models\BookModel;
use App\Models\CategoryModel;

/**
 * Home Controller
 * Controller untuk landing page, katalog, dan detail buku
 * 
 * Project: Kataraksa - Sistem Perpustakaan Digital
 * Author: Dimas
 */
class Home extends BaseController
{
    protected $bookModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->bookModel = new BookModel();
        $this->categoryModel = new CategoryModel();
        helper('hashid');
    }

    /**
     * Landing page
     */
    public function index()
    {
        // Get featured books (latest 8 books)
        $data = [
            'title' => 'Kataraksa - Sistem Perpustakaan Digital',
            'featuredBooks' => $this->bookModel
                ->select('books.*, categories.name as category_name')
                ->join('categories', 'categories.id = books.category_id', 'left')
                ->orderBy('books.created_at', 'DESC')
                ->limit(8)
                ->find(),
            'totalBooks' => $this->bookModel->countAll(),
            'totalCategories' => $this->categoryModel->countAll(),
        ];

        return view('public/home', $data);
    }

    /**
     * Katalog buku dengan search & filter kategori
     */
    public function catalog()
    {
        $search = $this->request->getGet('search');
        $categoryId = $this->request->getGet('category');

        // Build query
        $builder = $this->bookModel
            ->select('books.*, categories.name as category_name')
            ->join('categories', 'categories.id = books.category_id', 'left');

        // Apply search filter
        if ($search) {
            $builder->groupStart()
                ->like('books.title', $search)
                ->orLike('books.author', $search)
                ->orLike('books.isbn', $search)
                ->groupEnd();
        }

        // Apply category filter
        if ($categoryId) {
            $builder->where('books.category_id', $categoryId);
        }

        // Get books with pagination
        $books = $builder->orderBy('books.title', 'ASC')->paginate(12);

        $data = [
            'title' => 'Katalog Buku - Kataraksa',
            'books' => $books,
            'pager' => $this->bookModel->pager,
            'categories' => $this->categoryModel->orderBy('name', 'ASC')->findAll(),
            'search' => $search,
            'selectedCategory' => $categoryId,
        ];

        return view('public/catalog', $data);
    }

    /**
     * Detail buku
     */
    public function book($slug = null, $hash = null)
    {
        if (!$hash) {
            return redirect()->to('/catalog');
        }

        // Decode hashid to get actual ID
        $id = hashid_decode($hash);
        
        if (!$id) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Buku tidak ditemukan');
        }

        $book = $this->bookModel
            ->select('books.*, categories.name as category_name')
            ->join('categories', 'categories.id = books.category_id', 'left')
            ->find($id);

        if (!$book) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Buku tidak ditemukan');
        }

        // Verify slug matches (SEO canonical)
        $expectedSlug = url_title($book['title'], '-', true);
        if ($slug !== $expectedSlug) {
            return redirect()->to(book_url($book));
        }

        // Get related books (same category, exclude current book)
        $relatedBooks = $this->bookModel
            ->select('books.*, categories.name as category_name')
            ->join('categories', 'categories.id = books.category_id', 'left')
            ->where('books.category_id', $book['category_id'])
            ->where('books.id !=', $id)
            ->limit(4)
            ->find();

        $data = [
            'title' => $book['title'] . ' - Kataraksa',
            'book' => $book,
            'relatedBooks' => $relatedBooks,
        ];

        return view('public/book_detail', $data);
    }
}
