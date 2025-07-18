<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MoveController extends Controller
{
    /**
     * 特定の技の詳細ページを表示する (Bladeビューを返す)
     * 例: GET /moves/{name}
     *
     * @param string $name URLから渡される技の名前
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($name)
    {
        try {
            $response = Http::get("https://pokeapi.co/api/v2/move/{$name}/");

            if ($response->successful()) {
                $move = $response->json();

                // 日本語の技名を取得する（あれば）
                $japaneseMoveName = collect($move['names'])->firstWhere('language.name', 'ja')['name'] ?? $move['name'];

                // その技を覚えるポケモンのリスト
                $learnedByPokemon = [];
                if (isset($move['learned_by_pokemon'])) {
                    foreach ($move['learned_by_pokemon'] as $pokemonInfo) {
                        // ポケモンのIDをURLから抽出（例: "https://pokeapi.co/api/v2/pokemon/132/" から 132 を取得）
                        preg_match('/\/pokemon\/(\d+)\//', $pokemonInfo['url'], $matches);
                        $pokemonId = $matches[1] ?? null;

                        $learnedByPokemon[] = [
                            'name' => $pokemonInfo['name'],
                            'url' => route('pokemons.show', ['pokemon' => $pokemonId]), // ポケモン詳細ページへのリンク
                        ];
                    }
                }

                return view('moves.show', [
                    'move' => $move,
                    'japaneseMoveName' => $japaneseMoveName,
                    'learnedByPokemon' => $learnedByPokemon,
                ]);

            } else {
                // エラーハンドリング
                abort(404, 'Move not found.');
            }
        } catch (\Exception $e) {
            abort(500, 'An error occurred while fetching move data: ' . $e->getMessage());
        }
    }

    /**
     * 技の一覧ページを表示する (Bladeビューを返す)
     * 例: GET /moves
     *
     * @return \Illuminate\View\View
     */
    public function index() // Route::resource の 'index' アクションに対応
    {
        try {
            // PokeAPIから技のリストを取得 (例: 最初の200件)
            // 技も非常に多いため、必要に応じてlimitを調整してください
            $response = Http::get("https://pokeapi.co/api/v2/move/", [
                'limit' => 200, // 最初の200件を取得
                'offset' => 0,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $moveList = $data['results']; // 技の名前とURLのリスト

                // 各技の日本語名やタイプなどの詳細情報を取得（オプション）
                // ここではシンプルに名前だけ表示するため、追加のAPIリクエストは行いません。
                // もし技のタイプや効果なども一覧に表示したい場合は、
                // ここで各技のURLに対して追加のAPIリクエストを行う必要があります。
                // その場合、大量のAPIリクエストが発生する可能性があるので注意してください。

                return view('moves.index', compact('moveList'));

            } else {
                return view('error')->with('message', '技リストの取得に失敗しました。ステータスコード: ' . $response->status());
            }
        } catch (\Exception $e) {
            return view('error')->with('message', '技リストの取得中にエラーが発生しました: ' . $e->getMessage());
        }
    }
}