<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaAcara extends Model {
    use HasFactory;
    
    public function sidang() {
        return $this->belongsTo(Sidang::class, 'sidang_id'); // BA milik 1 Sidang
    }
}