<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Provinsi Indonesia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-blue-100 to-indigo-200 font-['Inter'] min-h-screen">
    <div class="container mx-auto p-6">
        <header class="text-center mb-8">
            <h1 class="text-4xl font-bold text-indigo-800">Daftar Provinsi Indonesia</h1>
            <p class="text-gray-600 mt-2">Kelola data provinsi dengan mudah dan cepat</p>
        </header>

        <div class="flex justify-end mb-6">
            <button onclick="showAddForm()" class="bg-indigo-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-indigo-700 transition duration-300">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Provinsi
            </button>
        </div>

        <div id="provinsi-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>

        <!-- Modal -->
        <div id="form-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-lg transform transition-all">
                <h2 id="form-title" class="text-2xl font-bold text-indigo-800 mb-6">Tambah Provinsi</h2>
                <form id="provinsi-form" class="space-y-4">
                    <input type="hidden" id="provinsi-id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Provinsi</label>
                        <input type="text" id="nama" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ibukota</label>
                        <input type="text" id="ibukota" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Populasi</label>
                        <input type="number" id="populasi" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Luas Wilayah (km²)</label>
                        <input type="number" id="luas_wilayah" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" step="0.01">
                    </div>
                    <div class="flex space-x-4">
                        <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-3 rounded-lg hover:bg-indigo-700 transition duration-300">Simpan</button>
                        <button type="button" onclick="closeForm()" class="flex-1 bg-gray-300 text-gray-800 px-4 py-3 rounded-lg hover:bg-gray-400 transition duration-300">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const BASE_URL = 'http://127.0.0.1:8000/api/provinsi';

        async function fetchProvinsis() {
            try {
                const response = await fetch(BASE_URL, { headers: { 'Accept': 'application/json' } });
                if (!response.ok) throw new Error('Gagal mengambil data');
                const result = await response.json();
                console.log('Data:', result);
                const provinsiList = document.getElementById('provinsi-list');
                provinsiList.innerHTML = '';
                if (!result.data || !Array.isArray(result.data)) {
                    provinsiList.innerHTML = '<p class="text-red-500 text-center">Data provinsi tidak tersedia.</p>';
                    return;
                }
                result.data.forEach(provinsi => {
                    if (!provinsi.id || !provinsi.nama || !provinsi.ibukota) {
                        console.warn('Data provinsi tidak lengkap:', provinsi);
                        return;
                    }
                    provinsiList.innerHTML += `
                        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300">
                            <h3 class="text-xl font-semibold text-indigo-700">${provinsi.nama}</h3>
                            <p class="text-gray-600 mt-1">Ibukota: ${provinsi.ibukota}</p>
                            <p class="text-gray-600">Populasi: ${provinsi.populasi ? provinsi.populasi.toLocaleString() : '-'}</p>
                            <p class="text-gray-600">Luas Wilayah: ${provinsi.luas_wilayah ? provinsi.luas_wilayah.toLocaleString() : '-'} km²</p>
                            <div class="mt-4 flex space-x-2">
                                <button onclick="editProvinsi(${provinsi.id})" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition duration-300">Edit</button>
                                <button onclick="deleteProvinsi(${provinsi.id})" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-300">Hapus</button>
                            </div>
                        </div>
                    `;
                });
            } catch (error) {
                console.error('Error fetching provinsis:', error);
                document.getElementById('provinsi-list').innerHTML = '<p class="text-red-500 text-center">Gagal memuat data provinsi.</p>';
            }
        }

        function showAddForm() {
            document.getElementById('form-modal').classList.remove('hidden');
            document.getElementById('form-title').textContent = 'Tambah Provinsi';
            document.getElementById('provinsi-form').reset();
            document.getElementById('provinsi-id').value = '';
        }

        function closeForm() {
            document.getElementById('form-modal').classList.add('hidden');
        }

        async function editProvinsi(id) {
            try {
                if (!id) throw new Error('ID provinsi tidak valid');
                const response = await fetch(`${BASE_URL}/${id}`, { headers: { 'Accept': 'application/json' } });
                if (!response.ok) throw new Error('Gagal mengambil data provinsi');
                const provinsi = await response.json();
                if (!provinsi.data) throw new Error('Data provinsi tidak ditemukan');
                document.getElementById('provinsi-id').value = provinsi.data.id || '';
                document.getElementById('nama').value = provinsi.data.nama || '';
                document.getElementById('ibukota').value = provinsi.data.ibukota || '';
                document.getElementById('populasi').value = provinsi.data.populasi || '';
                document.getElementById('luas_wilayah').value = provinsi.data.luas_wilayah || '';
                document.getElementById('form-title').textContent = 'Edit Provinsi';
                document.getElementById('form-modal').classList.remove('hidden');
            } catch (error) {
                console.error('Error fetching provinsi:', error);
                alert('Gagal memuat data provinsi: ' + error.message);
            }
        }

        async function deleteProvinsi(id) {
            if (confirm('Yakin ingin menghapus provinsi ini?')) {
                try {
                    const response = await fetch(`${BASE_URL}/${id}`, {
                        method: 'DELETE',
                        headers: { 'Accept': 'application/json' }
                    });
                    if (!response.ok) throw new Error('Gagal menghapus provinsi');
                    fetchProvinsis();
                } catch (error) {
                    console.error('Error deleting provinsi:', error);
                    alert('Gagal menghapus provinsi: ' + error.message);
                }
            }
        }

        document.getElementById('provinsi-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('provinsi-id').value;
            const data = {
                nama: document.getElementById('nama').value,
                ibukota: document.getElementById('ibukota').value,
                populasi: document.getElementById('populasi').value ? parseInt(document.getElementById('populasi').value) : null,
                luas_wilayah: document.getElementById('luas_wilayah').value ? parseFloat(document.getElementById('luas_wilayah').value) : null,
            };

            const method = id ? 'PUT' : 'POST';
            const url = id ? `${BASE_URL}/${id}` : BASE_URL;

            try {
                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data),
                });
                if (!response.ok) {
                    const errorData = await response.json();
                    console.error('Error:', errorData);
                    alert('Gagal menyimpan data: ' + JSON.stringify(errorData.errors || errorData.error));
                    return;
                }
                fetchProvinsis();
                closeForm();
            } catch (error) {
                console.error('Fetch error:', error);
                alert('Terjadi kesalahan jaringan: ' + error.message);
            }
        });

        fetchProvinsis();
    </script>
</body>

</html>
