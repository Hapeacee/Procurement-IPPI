// Fungsi untuk membuka modal dan mengisi data
function openUpdateModal(id, itemId, transactionType, quantity) {
    var modal = document.getElementById('updateModal');
    var backdrop = document.querySelector('.modal-backdrop');
    
    // Isi form dengan data yang diterima
    document.getElementById('update-id').value = id;
    document.getElementById('update-transaction-type').value = transactionType;
    document.getElementById('update-quantity').value = quantity;
    
    // Tampilkan modal dan backdrop
    modal.classList.add('show');
    backdrop.classList.add('show');
}

// Fungsi untuk menutup modal
function closeUpdateModal() {
    var modal = document.getElementById('updateModal');
    var backdrop = document.querySelector('.modal-backdrop');
    modal.classList.remove('show');
    backdrop.classList.remove('show');
}

// Event listener untuk tombol 'Save Changes'
document.getElementById('saveUpdateBtn').addEventListener('click', function() {
    // Ambil data dari form
    var formData = new FormData(document.getElementById('updateForm'));
    
    // Kirim data menggunakan AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'alldata.php', true); // Sesuaikan dengan URL tujuan server
    xhr.onload = function() {
        if (xhr.status == 200) {
            // Jika sukses, tutup modal dan refresh data atau tampilkan pesan sukses
            closeUpdateModal();
            alert('Data updated successfully');
            // Lakukan hal lain seperti merefresh tabel atau menampilkan hasil update
        } else {
            alert('Failed to update data');
        }
    };
    xhr.send(formData);
});
