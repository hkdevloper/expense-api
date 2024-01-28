<?php

use App\Models\TransactionCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTransactionCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('transaction_categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name', 100);
            $table->enum('category_type', ['Income', 'Expense'])->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });

        /// Create Default Categories
        DB::table('transaction_categories')->insert([
            ['category_name' => 'Utilities', 'category_type' => 'Expense', 'created_by' => 1, 'updated_by' => 1],
            ['category_name' => 'Groceries', 'category_type' => 'Expense', 'created_by' => 1, 'updated_by' => 1],
            ['category_name' => 'Entertainment', 'category_type' => 'Expense', 'created_by' => 1, 'updated_by' => 1],
            ['category_name' => 'Healthcare', 'category_type' => 'Expense', 'created_by' => 1, 'updated_by' => 1],
            ['category_name' => 'Transportation', 'category_type' => 'Expense', 'created_by' => 1, 'updated_by' => 1],
            ['category_name' => 'Other Expenses', 'category_type' => 'Expense', 'created_by' => 1, 'updated_by' => 1],
            ['category_name' => 'Salary', 'category_type' => 'Income', 'created_by' => 1, 'updated_by' => 1],
            ['category_name' => 'Freelance Income', 'category_type' => 'Income', 'created_by' => 1, 'updated_by' => 1],
            ['category_name' => 'Investment Returns', 'category_type' => 'Income', 'created_by' => 1, 'updated_by' => 1],
            ['category_name' => 'Gifts Received', 'category_type' => 'Income', 'created_by' => 1, 'updated_by' => 1],
            ['category_name' => 'Other Income', 'category_type' => 'Income', 'created_by' => 1, 'updated_by' => 1],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_categories');
    }
}
