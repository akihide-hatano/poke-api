<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // LaravelのHTTPクライアント

class PokemonController extends Controller
{
    /**
     * 指定されたポケモンのデータをPokeAPIから取得して返す (API用)
     * 例: /api/pokemon/pikachu
     *
     * @param string $nameOrId ポケモンの名前またはID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($nameOrId)
    {
        try {
            $response = Http::get("https://pokeapi.co/api/v2/pokemon/{$nameOrId}/");

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json([
                    'message' => 'Failed to fetch Pokemon data from PokeAPI.',
                    'status' => $response->status()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching Pokemon data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ポケモンの一覧をPokeAPIから取得して返す (API用)
     * 例: /api/pokemons?limit=20&offset=0
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 20);
        $offset = $request->query('offset', 0);

        try {
            $response = Http::get("https://pokeapi.co/api/v2/pokemon/", [
                'limit' => $limit,
                'offset' => $offset,
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json([
                    'message' => 'Failed to fetch Pokemon list from PokeAPI.',
                    'status' => $response->status()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching Pokemon list.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ポケモン一覧を表示するページ (Bladeビューを返す)
     * 例: /pokemons-list
     *
     * @return \Illuminate\View\View
     */
    public function showIndexPage()
    {
        try {
            // PokeAPIから最初の20件のポケモンリストを取得
            // ここでは直接PokeAPIを叩きます。もしAPIエンドポイントのindexメソッドを再利用したい場合は、
            // 内部的にHTTPリクエストを再度発行するか、ロジックをサービス層に分離する必要があります。
            $response = Http::get("https://pokeapi.co/api/v2/pokemon/", [
                'limit' => 20, // 最初の20件を取得
                'offset' => 0,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $pokemonList = $data['results']; // ポケモンの名前とURLのリスト

                // 各ポケモンの詳細データを取得 (画像など)
                $detailedPokemonList = [];
                foreach ($pokemonList as $pokemon) {
                    $detailResponse = Http::get($pokemon['url']);
                    if ($detailResponse->successful()) {
                        $detailedPokemonList[] = $detailResponse->json();
                    }
                }

                // 取得したデータをビューに渡す
                return view('pokemon.index', compact('detailedPokemonList'));

            } else {
                // エラーページにリダイレクトするか、エラーメッセージをビューに渡す
                return view('error')->with('message', 'ポケモンリストの取得に失敗しました。ステータスコード: ' . $response->status());
            }
        } catch (\Exception $e) {
            return view('error')->with('message', 'ポケモンリストの取得中にエラーが発生しました: ' . $e->getMessage());
        }
    }
}