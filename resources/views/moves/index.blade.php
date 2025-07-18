<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-gray-800 text-center mb-8 text-4xl font-extrabold drop-shadow-md">技図鑑</h1>

                {{-- エラーメッセージの表示 --}}
                @if(session('error'))
                    <div class="bg-red-100 text-red-700 border border-red-300 p-3 mb-5 rounded-md text-center">
                        {{ session('error') }}
                    </div>
                @endif

                @if(isset($moveList) && count($moveList) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-5">
                        @foreach($moveList as $move)
                            {{-- 各技カードを詳細ページへのリンクにする --}}
                            <a href="{{ route('moves.show', ['move' => $move['name']]) }}" class="move-card bg-white rounded-lg shadow-md overflow-hidden text-center p-4 transition-all duration-200 ease-in-out border border-gray-200 no-underline text-inherit block hover:translate-y-[-5px] hover:shadow-xl">
                                <h2 class="text-xl text-gray-700 mb-1 capitalize">{{ ucfirst(str_replace('-', ' ', $move['name'])) }}</h2>
                                <p class="text-sm text-gray-600">詳細を見る →</p>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 text-lg p-12">技データが見つかりませんでした。</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
