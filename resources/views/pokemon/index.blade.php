<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ポケモン図鑑</title>
    <!-- Tailwind CSS (Breezeが導入されていれば自動的に読み込まれます) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f0f0f0; margin: 0; padding: 20px; display: flex; flex-wrap: wrap; justify-content: center; align-items: flex-start; min-height: 100vh; }
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        .pokemon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .pokemon-card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            text-align: center;
            padding: 15px;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            border: 1px solid #e0e0e0;
        }
        .pokemon-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .pokemon-card img {
            max-width: 100px;
            height: auto;
            margin: 0 auto 10px auto;
            display: block;
        }
        .pokemon-card h2 {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 5px;
            text-transform: capitalize; /* 最初の文字を大文字に */
        }
        .pokemon-card p {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 3px;
        }
        .pokemon-card ul {
            list-style: none;
            padding: 0;
            margin-top: 10px;
        }
        .pokemon-card ul li {
            background-color: #e6f7ff;
            color: #007bff;
            display: inline-block;
            padding: 4px 8px;
            border-radius: 5px;
            margin: 2px;
            font-size: 0.8em;
            text-transform: capitalize;
        }
        .no-pokemon {
            text-align: center;
            color: #777;
            font-size: 1.2rem;
            padding: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ポケモン図鑑</h1>

        @if(isset($detailedPokemonList) && count($detailedPokemonList) > 0)
            <div class="pokemon-grid">
                @foreach($detailedPokemonList as $pokemon)
                    <div class="pokemon-card">
                        @if(isset($pokemon['sprites']['front_default']))
                            <img src="{{ $pokemon['sprites']['front_default'] }}" alt="{{ $pokemon['name'] }}">
                        @else
                            <img src="https://placehold.co/100x100/eeeeee/333333?text=No+Image" alt="No Image">
                        @endif
                        <h2>{{ ucfirst($pokemon['name']) }}</h2>
                        <p>ID: {{ $pokemon['id'] }}</p>
                        <p>高さ: {{ $pokemon['height'] / 10 }} m</p>
                        <p>重さ: {{ $pokemon['weight'] / 10 }} kg</p>

                        <h3>タイプ</h3>
                        <ul>
                            @foreach($pokemon['types'] as $typeInfo)
                                <li>{{ ucfirst($typeInfo['type']['name']) }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        @else
            <p class="no-pokemon">ポケモンデータが見つかりませんでした。</p>
        @endif
    </div>
</body>
</html>
