<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Todo;
use App\Models\Category;
use App\Models\Note;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cria a usuária mestre felina
        $user = User::firstOrCreate(
            ['email' => 'smiliguida@skyflow.com'],
            [
                'name' => 'Smiliguida',
                'password' => bcrypt('12345678'),
            ]
        );

        // 2. Costurando a realidade com Categorias
        $catDev = Category::create([
            'name' => 'Desenvolvimento', 'color' => '#3b82f6', 'user_id' => $user->id
        ]);
        $catPet = Category::create([
            'name' => 'Missões Felinas', 'color' => '#f43f5e', 'user_id' => $user->id
        ]);
        $catProj = Category::create([
            'name' => 'Projetos Pessoais', 'color' => '#10b981', 'user_id' => $user->id
        ]);

        // 3. Tarefas de extrema importância alocadas em categorias
        $tarefas = [
            ['title' => 'Finalizar a Bankai do Kanban', 'status' => 'em-andamento', 'priority' => 'highest', 'cat_id' => $catDev->id],
            ['title' => 'Comprar sachê de salmão premium', 'status' => 'a-fazer', 'priority' => 'highest', 'cat_id' => $catPet->id],
            ['title' => 'Derrubar o copo da mesa', 'status' => 'concluido', 'priority' => 'high', 'cat_id' => $catPet->id],
            ['title' => 'Dormir no teclado durante o código', 'status' => 'em-andamento', 'priority' => 'highest', 'cat_id' => $catPet->id],
            ['title' => 'Revisar arquitetura do SkyFlow', 'status' => 'a-fazer', 'priority' => 'medium', 'cat_id' => $catProj->id],
        ];

        foreach ($tarefas as $tarefa) {
            Todo::create([
                'title' => $tarefa['title'],
                'description' => 'Descrição gerada automaticamente pela Benihime.',
                'status' => $tarefa['status'],
                'priority' => $tarefa['priority'],
                'is_completed' => $tarefa['status'] === 'concluido' ? true : false,
                'category_id' => $tarefa['cat_id'], // Vinculando a categoria
                'user_id' => $user->id,
            ]);
        }

        // 4. Adicionando Anotações Recentes (Notas Rápidas)
        Note::create([
            'title' => 'Ideias para o SkyFlow',
            'content' => 'Implementar drag-and-drop avançado e dark mode para não ofuscar a visão noturna felina.',
            'user_id' => $user->id
        ]);

        Note::create([
            'title' => 'Plano de Dominação',
            'content' => '1. Miar às 4h da manhã. 2. Pedir comida e não comer. 3. Derrubar objetos frágeis.',
            'user_id' => $user->id
        ]);
        Note::create([
            'title' => 'Pular nas Pernas de Júlia',
            'content' => '1. Esperar ela sentar. 2. Pular de surpresa. 3. Correr para o sofá.',
            'user_id' => $user->id
        ]);
        Note::create([
            'title' => 'Subir nos móveis de casa',
            'content' => '1. Sofá. 2. Cristáleira da sala. 3. Derrubar objetos frágeis.',
            'user_id' => $user->id
        ]);
        Note::create([
            'title' => 'Dormir na favelinha',
            'content' => '1. Miar às 4h da manhã. 2. Pedir comida e não comer. 3. Derrubar objetos frágeis.',
            'user_id' => $user->id
        ]);
    }
}
