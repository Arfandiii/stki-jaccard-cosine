@extends('admin.layouts.app')

@section('content')
    <div class="content ml-12 transform ease-in-out duration-500 pt-25 px-2 md:px-5 pb-4">
        <div class="max-w-7xl mx-auto px-4 py-8 space-y-6">
            {{-- FORM QUERY + FILTER --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-4">Debug Pencarian Surat</h1>

                <form action="{{ route('admin.search.simple') }}" method="GET" class="flex flex-col gap-4 md:flex-row">
                    <input type="text" name="query" requiblue 5
                        class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Ketik kata kunci..." value="{{ request('query') }}">

                    <select name="filter"
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all" {{ ($filter ?? 'all') === 'all' ? 'selected' : '' }}>Semua Surat</option>
                        <option value="masuk" {{ ($filter ?? 'all') === 'masuk' ? 'selected' : '' }}>Hanya Surat Masuk
                        </option>
                        <option value="keluar"{{ ($filter ?? 'all') === 'keluar' ? 'selected' : '' }}>Hanya Surat Keluar
                        </option>
                    </select>

                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition cursor-pointer">
                        Proses
                    </button>
                </form>
            </div>

            @if (isset($query))
                {{-- TABEL 1: DATA Surat & QUERY --}}
                <div class="rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-2xl font-bold">1. Data Surat & Query</h2>

                    {{-- Baris Query --}}
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full divide-y divide-gray-200 border text-sm">
                            <thead class="divide-y divide-gray-600 bg-blue-500 text-white">
                                <tr>
                                    <th class="border p-3 text-center text-lg font-semibold">Query</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border p-3 text-center text-2xl">{{ $query }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border text-sm">
                            <thead class="divide-y divide-gray-600 bg-blue-500 text-white">
                                <tr>
                                    <th class="border p-3 text-center font-semibold">No</th>
                                    <th class="border p-3 font-semibold">ID</th>
                                    <th class="border p-3 font-semibold">Tipe</th>
                                    <th class="border p-3 font-semibold">Isi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach ($documents as $index => $doc)
                                    <tr class="transition hover:bg-blue-50">
                                        <td class="border p-3 text-center">{{ $index + 1 }}</td>
                                        <td class="border p-3">{{ $doc['id'] }}</td>
                                        <td class="border p-3 text-center">
                                            {{ str_contains($doc['id'], 'SM') ? 'Masuk' : 'Keluar' }}
                                        </td>
                                        <td class="border p-3">{{ $doc['isi'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TABEL 2: PREPROCESSING DETAIL --}}
                <div class="rounded-lg bg-white p-6 shadow">
                    <h1 class="mb-4 text-2xl font-bold">2. Hasil Preprocessing Surat</h1>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border text-sm">
                            <thead class="bg-blue-500 text-white">
                                <tr>
                                    <th class="border p-3 text-center font-semibold">No</th>
                                    <th class="border p-3 text-center font-semibold">Tipe Surat</th>
                                    <th class="border p-3 font-semibold">Isi</th>
                                    <th class="border p-3 font-semibold">Tokenizing</th>
                                    <th class="border p-3 font-semibold">Filtering</th>
                                    <th class="border p-3 font-semibold">Stopword Removal</th>
                                    <th class="border p-3 font-semibold">Stemming</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach ($documentsdetailed as $idx => $doc)
                                    <tr class="transition hover:bg-blue-50">
                                        <td class="border p-3 text-center">{{ $idx + 1 }}</td>
                                        <td class="border p-3 text-center">{{ $doc['nomor'] }}</td>
                                        <td class="border p-3">{{ $doc['isi'] }}</td>
                                        <td class="border p-3">|
                                            {{ implode(' | ', $doc['preprocessing']['case_folding_and_tokenizing']) }} |
                                        </td>
                                        <td class="border p-3">| {{ implode(' | ', $doc['preprocessing']['filtering']) }} |
                                        </td>
                                        <td class="border p-3">|
                                            {{ implode(' | ', $doc['preprocessing']['stopword_removal']) }} |
                                        </td>
                                        <td class="border p-3">| {{ implode(' | ', $doc['preprocessing']['stemming']) }} |
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TABEL 3: TOKENS PER Surat --}}
                <div class="mt-10 overflow-x-auto rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-2xl font-bold">3. Tokens per Surat</h2>
                    <table class="min-w-full divide-y divide-gray-200 border text-sm">
                        <thead class="divide-y divide-gray-600 bg-blue-500 text-white">
                            <tr>
                                <th class="border p-3 text-center font-semibold">No</th>
                                <th class="border p-3 font-semibold">Data</th>
                                <th class="border p-3 font-semibold">Tokens</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($documents as $index => $doc)
                                <tr class="transition hover:bg-blue-50">
                                    <td class="border p-3 text-center">{{ $index + 1 }}</td>
                                    <td class="border p-3">
                                        ID: {{ $doc['id'] }}<br>
                                        Tipe: {{ str_contains($doc['id'], 'SM') ? 'Masuk' : 'Keluar' }}<br>
                                        Isi: {{ $doc['isi'] }}
                                    </td>
                                    <td class="border p-3">{{ implode(', ', $doc['tokens']) }}</td>
                                </tr>
                            @endforeach

                            {{-- Baris Query --}}
                            <tr class="bg-yellow-100 font-semibold transition hover:bg-blue-50">
                                <td class="border p-3 text-center">Q</td>
                                <td class="border p-3">Query: {{ $query }}</td>
                                <td class="border p-3">{{ implode(', ', $queryTokens) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- TABEL 4: TF --}}
                <div class="mt-10 overflow-x-auto rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-2xl font-bold">4. Term Frequency (TF)</h2>
                    <div class="mb-6 rounded-lg border-l-4 border-blue-500 bg-blue-50 p-5 text-sm">
                        <p class="mb-2 font-bold text-blue-800">Rumus & Penjelasan</p>

                        <code class="rounded bg-blue-100 px-2 py-1 text-base">
                            TF(t,d) = jumlah kemunculan term t dalam dokumen d
                        </code>

                        <ul class="mt-3 list-disc space-y-1 pl-5 text-gray-700">
                            <li>Hitung <strong>raw count</strong> – berapa kali kata t muncul di dokumen d.</li>
                            <li>Nilai minimum = 0 (kata tidak ada), nilai maksimum = tak terbatas.</li>
                            <li>Tabel di bawah menunjukkan frekuensi absolut setiap kata pada 20 dokumen + query.</li>
                        </ul>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 border text-sm">
                        <thead class="divide-y divide-gray-600 bg-blue-500 text-white">
                            <tr>
                                <th class="border p-3 text-center font-semibold">Term</th>
                                @for ($i = 1; $i <= count($documents); $i++)
                                    <th class="border p-3 text-center font-semibold">D{{ $i }}</th>
                                @endfor
                                <th class="border p-3 text-center font-semibold">Query</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($tfTable as $row)
                                <tr class="transition hover:bg-blue-50">
                                    <td class="border p-3 text-center">{{ $row['term'] }}</td>
                                    @for ($j = 1; $j <= count($documents); $j++)
                                        <td class="border p-3 text-center">{{ $row["D$j"] }}</td>
                                    @endfor
                                    <td class="border p-3 text-center">{{ $row['Q'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- TABEL 5: TF WEIGHT --}}
                <div class="mt-10 overflow-x-auto rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-2xl font-bold">5. Term Frequency Weight (TF Weight)</h2>
                    <div class="mb-6 rounded-lg border-l-4 border-green-500 bg-green-50 p-5 text-sm">
                        <p class="mb-2 font-bold text-green-800">Rumus & Penjelasan</p>

                        <code class="rounded bg-green-100 px-2 py-1 text-base">
                            W(t,d) = 1 + log₁₀(TF(t,d)) &nbsp; &nbsp; jika TF > 0<br>
                            0 &nbsp; &nbsp; jika TF = 0
                        </code>

                        <ul class="mt-3 list-disc space-y-1 pl-5 text-gray-700">
                            <li>Menekan lonjakan frekuensi: kata yang muncul 10× tidak 10× lebih penting dari 1×.</li>
                            <li>Hasil ≥ 0; jika TF = 1 → W = 1; TF = 10 → W ≈ 2; TF = 100 → W = 3.</li>
                            <li>Langkah ini menghasilkan bobot <em>logaritmik</em> sebelum dikalikan IDF.</li>
                        </ul>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 border text-sm">
                        <thead class="divide-y divide-gray-600 bg-blue-500 text-white">
                            <tr>
                                <th class="border p-3 text-center font-semibold">Term</th>
                                @for ($i = 1; $i <= count($documents); $i++)
                                    <th class="border p-3 text-center font-semibold">D{{ $i }}</th>
                                @endfor
                                <th class="border p-3 text-center font-semibold">Query</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($tfWeightTable as $row)
                                <tr class="transition hover:bg-blue-50">
                                    <td class="border p-3 text-center">{{ $row['term'] }}</td>
                                    @for ($j = 1; $j <= count($documents); $j++)
                                        <td class="border p-3 text-center">{{ $row["D$j"] }}</td>
                                    @endfor
                                    <td class="border p-3 text-center">{{ $row['Q'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- TABEL 6: DF & IDF --}}
                <div class="mt-10 overflow-x-auto rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-2xl font-bold">6. Document Frequency (DF) dan Inverse Document Frequency (IDF)
                    </h2>
                    <div class="mb-6 rounded-lg border-l-4 border-yellow-500 bg-yellow-50 p-5 text-sm">
                        <p class="mb-2 font-bold text-yellow-800">Rumus & Penjelasan</p>

                        <code class="rounded bg-yellow-100 px-2 py-1 text-base">
                            DF(t) = banyak dokumen yang mengandung term t<br>
                            IDF(t) = log₁₀(N / DF(t))
                        </code>

                        <ul class="mt-3 list-disc space-y-1 pl-5 text-gray-700">
                            <li>DF = hitung dokumen (bukan kalimat) yang mengandung kata t.</li>
                            <li>N = total dokumen (di sini 20).</li>
                            <li>IDF tinggi → kata jarang; IDF rendah → kata umum (mis. “dan”, “di”).</li>
                            <li>Stop-word biasanya IDF ≈ 0 karena muncul di hampir semua dokumen.</li>
                        </ul>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 border text-sm">
                        <thead class="divide-y divide-gray-600 bg-blue-500 text-white">
                            <tr>
                                <th class="border p-3 text-center font-semibold">Term</th>
                                <th class="border p-3 text-center font-semibold">DF</th>
                                <th class="border p-3 text-center font-semibold">IDF</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($idfTable as $row)
                                <tr class="transition hover:bg-blue-50">
                                    <td class="border p-3 text-center">{{ $row['term'] }}</td>
                                    <td class="border p-3 text-center">{{ $row['df'] }}</td>
                                    <td class="border p-3 text-center">{{ $row['idf'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- TABEL 7: TF-IDF --}}
                <div class="mt-10 overflow-x-auto rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-2xl font-bold">7. TF-IDF</h2>
                    <div class="mb-6 rounded-lg border-l-4 border-purple-500 bg-purple-50 p-5 text-sm">
                        <p class="mb-2 font-bold text-purple-800">Rumus & Penjelasan</p>

                        <code class="rounded bg-purple-100 px-2 py-1 text-base">
                            TF-IDF(t,d) = W(t,d) × IDF(t)
                        </code>

                        <ul class="mt-3 list-disc space-y-1 pl-5 text-gray-700">
                            <li>Menggabungkan “seberapa sering” (TF-Weight) dan “seberapa unik” (IDF).</li>
                            <li>Kata yang sering muncul DI DOKUMEN INI tapi JARANG di dokumen lain → nilai tinggi.</li>
                            <li>Kata yang muncul di semua dokumen → IDF ≈ 0 → TF-IDF ≈ 0 (tidak penting).</li>
                            <li>Hasil digunakan sebagai bobot vektor untuk perhitungan similarity.</li>
                        </ul>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 border text-sm">
                        <thead class="divide-y divide-gray-600 bg-blue-500 text-white">
                            <tr>
                                <th class="border p-3 text-center font-semibold">Term</th>
                                @for ($i = 1; $i <= count($documents); $i++)
                                    <th class="border p-3 text-center font-semibold">D{{ $i }}</th>
                                @endfor
                                <th class="border p-3 text-center font-semibold">Query</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($tfidfTable as $row)
                                <tr class="transition hover:bg-blue-50">
                                    <td class="border p-3 text-center">{{ $row['term'] }}</td>
                                    @for ($j = 1; $j <= count($documents); $j++)
                                        <td class="border p-3 text-center">{{ $row["D$j"] }}</td>
                                    @endfor
                                    <td class="border p-3 text-center">{{ $row['Q'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- TABEL 8: TF-IDF NORMALISASI --}}
                <div class="mt-10 overflow-x-auto rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-2xl font-bold">8. TF-IDF Normalisasi</h2>

                    <div class="mb-6 rounded-lg border-l-4 border-indigo-500 bg-indigo-50 p-5 text-sm">
                        <p class="mb-2 font-bold text-indigo-800">Rumus & Penjelasan</p>

                        <code class="rounded bg-indigo-100 px-2 py-1 text-base">
                            TF-IDF<sub>norm</sub>(t,d) =
                            TF-IDF(t,d) / √(∑ TF-IDF(tᵢ,d)²)
                        </code>

                        <ul class="mt-3 list-disc space-y-1 pl-5 text-gray-700">
                            <li>Melakukan normalisasi panjang vektor dokumen dan query.</li>
                            <li>Setiap dokumen dan query memiliki panjang vektor = 1.</li>
                            <li>Menghilangkan bias dokumen panjang.</li>
                            <li>Nilai inilah yang digunakan langsung pada Cosine Similarity.</li>
                        </ul>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 border text-sm">
                        <thead class="divide-y divide-gray-600 bg-blue-600 text-white">
                            <tr>
                                <th class="border p-3 text-center font-semibold">Term</th>
                                @for ($i = 1; $i <= count($documents); $i++)
                                    <th class="border p-3 text-center font-semibold">D{{ $i }}</th>
                                @endfor
                                <th class="border p-3 text-center font-semibold">Query</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($tfidfNormTable as $row)
                                <tr class="transition hover:bg-indigo-50">
                                    <td class="border p-3 text-center font-medium">
                                        {{ $row['term'] }}
                                    </td>

                                    @for ($j = 1; $j <= count($documents); $j++)
                                        <td class="border p-3 text-center">
                                            {{ $row["D$j"] }}
                                        </td>
                                    @endfor

                                    <td class="border p-3 text-center font-semibold">
                                        {{ $row['Q'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- TABEL 9: COSINE SIMILARITY --}}
                <div class="mt-10 overflow-x-auto rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-2xl font-bold">9. Cosine Similarity ({{ ucfirst($filter) }})</h2>
                    <div class="mb-6 rounded-lg border-l-4 border-indigo-500 bg-indigo-50 p-5 text-sm">
                        <p class="mb-2 font-bold text-indigo-800">Rumus & Penjelasan</p>

                        <code class="rounded bg-indigo-100 px-2 py-1 text-base">
                            cos θ = (Vq · Vd) / (|Vq| × |Vd|)
                        </code>

                        <ul class="mt-3 list-disc space-y-1 pl-5 text-gray-700">
                            <li>Vq = vektor TF-IDF query; Vd = vektor TF-IDF dokumen.</li>
                            <li>Titik (·) = dot-product; |V| = panjang vektor (akar dari jumlah kuadrat elemen).</li>
                            <li>Hasil 0–1: 1 = sangat mirip, 0 = tidak ada kesamaan.</li>
                            <li>Menentukan peringkat dokumen yang paling relevan terhadap query.</li>
                        </ul>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 border text-sm">
                        <thead class="divide-y divide-gray-600 bg-blue-500 text-white">
                            <tr>
                                <th class="border p-3 text-center font-semibold">Dokumen</th>
                                <th class="border p-3 text-center font-semibold">Tipe</th>
                                <th class="border p-3 text-center font-semibold">Cosine Similarity</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($cosineSimilarities as $idx => $sim)
                                @php
                                    // ambil index asli (0-19) lalu cek tipe-nya
                                    $realIdx = (int) substr($sim['doc'], 1) - 1; // "D4" → 3
                                    $tipe = $documents[$realIdx]['tipe']; // masuk / keluar
                                @endphp
                                <tr class="transition hover:bg-blue-50">
                                    <td class="border p-3 text-center">{{ $sim['doc'] }}</td>
                                    <td class="border p-3 text-center">
                                        <span class="rounded-full bg-gray-200 px-2 py-1 text-xs font-semibold">
                                            {{ ucfirst($tipe) }}
                                        </span>
                                    </td>
                                    <td class="border p-3 text-center">{{ $sim['similarity'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- TABEL 10: JACCARD SIMILARITY --}}
                <div class="mt-10 overflow-x-auto rounded-lg bg-white p-6 shadow">
                    <h2 class="mb-4 text-2xl font-bold">10. Jaccard Similarity ({{ ucfirst($filter) }})</h2>

                    <div class="mb-4 rounded border-l-4 border-teal-500 bg-teal-50 p-4 text-sm">
                        <p class="mb-2 font-bold text-teal-800">Rumus & Penjelasan</p>
                        <code class="text-lg">J(A,B) = |A ∩ B| / |A ∪ B|</code>
                        <ul class="mt-2 list-disc space-y-1 pl-5 text-gray-700">
                            <li>A = set kata dokumen, B = set kata query (unik).</li>
                            <li>Nilai 0–1; tidak peduli frekuensi, hanya keberadaan kata.</li>
                            <li>Cocok untuk pencarian yang menekan kata umum (stop-word tetap 0 jika di-set).</li>
                        </ul>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 border text-sm">
                        <thead class="divide-y divide-gray-600 bg-blue-500 text-white">
                            <tr>
                                <th class="border p-3 text-center font-semibold">Dokumen</th>
                                <th class="border p-3 text-center font-semibold">Tipe</th>
                                <th class="border p-3 text-center font-semibold">Jaccard Similarity</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($jaccardSimilarities as $jac)
                                <tr class="transition hover:bg-blue-50">
                                    <td class="border p-3 text-center">{{ $jac['doc'] }}</td>
                                    <td class="border p-3 text-center">
                                        <span class="rounded-full bg-gray-200 px-2 py-1 text-xs font-semibold">
                                            {{ ucfirst($jac['tipe']) }}
                                        </span>
                                    </td>
                                    <td class="border p-3 text-center">{{ $jac['jaccard'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
