var popupRegis = document.getElementById("popupRegis");
var popupLogin = document.getElementById("popupLogin");

var openPopupBtnRegis = document.getElementById("open-popup-regis");
var openPopupBtnLogin = document.getElementById("open-popup-login");

var closeBtns = document.querySelectorAll(".close-btn");

openPopupBtnRegis.onclick = function () {
  popupRegis.style.display = "flex";
};
openPopupBtnLogin.onclick = function () {
  popupLogin.style.display = "flex";
};

closeBtns.forEach(function (closeBtn) {
  closeBtn.onclick = function () {
    popupRegis.style.display = "none";
    popupLogin.style.display = "none";
  };
});

document.getElementById('searchForm').addEventListener('submit', function (e) {
  e.preventDefault(); // Mencegah pengiriman form default

  const keyword = document.getElementById('searchKeyword').value;
  if (keyword.trim() === '') {
      alert('Mohon masukkan kata kunci pencarian.');
      return;
  }


  const xhr = new XMLHttpRequest();
  xhr.open('GET', `index.php?keyword=${encodeURIComponent(keyword)}`, true);
  xhr.onload = function () {
      if (xhr.status === 200) {
          const data = JSON.parse(xhr.responseText);
          if (data.length > 0) {

              const firstDestination = data[0];
              window.location.href = `pages/haltiket.php?destination=${encodeURIComponent(firstDestination.id)}`;
          } else {
              alert('Destinasi tidak ditemukan. Silakan coba kata kunci lain.');
          }
      } else {
          alert('Terjadi kesalahan saat pencarian. Silakan coba lagi.');
      }
  };
  xhr.send();
});



// window.onclick = function(event) {
//     if (event.target == popupRegis) {
//         popupRegis.style.display = "none";
//     }
//     if (event.target == popupLogin) {
//         popupLogin.style.display = "none";
//     }
// }
