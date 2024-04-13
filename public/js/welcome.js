$(document).ready(function() {
  $(".panel-collapse").on("show.bs.collapse", function() {
    $(this).siblings(".panel-heading").addClass("active");
  });

  $(".panel-collapse").on("hide.bs.collapse", function() {
    $(this).siblings(".panel-heading").removeClass("active");
  });

  // Mengubah toggle behavior untuk elemen yang menggunakan data-toggle="toggle"
  $("body").on("click", '[data-toggle="toggle"]', function(event) {
    event.preventDefault(); // Menghentikan perilaku default dari a href
    var target = $(this).attr("href"); // Mengambil id target dari href
    $(target).collapse("toggle"); // Menampilkan/menyembunyikan elemen target
  });

  // Mengubah behavior untuk elemen yang menggunakan data-toggle="collapse"
  $("body").on("click", '[data-toggle="collapse"]', function(event) {
    event.preventDefault(); // Menghentikan perilaku default dari a href
    var target = $(this).attr("href"); // Mengambil id target dari href
    $(target).collapse("toggle"); // Menampilkan/menyembunyikan elemen target
  });

  function signOut() {
    // Lakukan proses sign out di sini, seperti menghapus token, membersihkan sesi, dll.
    alert("Anda berhasil sign out");
    // Redirect ke halaman login atau halaman lain setelah sign out
    window.location.href = "/logsign.html";
  }

  $("#btn-sign-out").on("click", signOut);
});
