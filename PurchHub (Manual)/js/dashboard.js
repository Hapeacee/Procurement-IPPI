const dropdownToggle = document.querySelector(".dropdown-toggle");
const dropdownMenu = document.querySelector(".dropdown-menu");

document.addEventListener("DOMContentLoaded", () => {
    const dropdown = document.querySelector('.navbar .dropdown');
    
    dropdown.addEventListener('click', function() {
        this.classList.toggle('show');  // Toggle dropdown menu
    });

    const dropdowns = document.querySelectorAll(".dropdown");

    dropdowns.forEach((dropdown) => {
        const toggle = dropdown.querySelector(".dropdown-toggle");

        toggle.addEventListener("click", (event) => {
            event.preventDefault(); // Prevent default link behavior

            // Toggle active class
            dropdown.classList.toggle("active");

            // Close other dropdowns
            dropdowns.forEach((otherDropdown) => {
                if (otherDropdown !== dropdown) {
                    otherDropdown.classList.remove("active");
                }
            });
        });
    });
    
});

// Toggle Menu
document.querySelector('.hamburger').addEventListener('click', () => {
    document.querySelector('.navbar-menu').classList.toggle('active');
});

// Dropdown functionality
document.querySelectorAll('.dropdown-toggle').forEach(item => {
    item.addEventListener('click', event => {
        event.preventDefault(); // Prevent default anchor behavior
        const dropdown = item.nextElementSibling; // Target the dropdown menu
        dropdown.classList.toggle('active');
    });
});

// Menambahkan event listener ke elemen dengan class "dropdown-toggle"
document.querySelector('.dropdown-toggle').addEventListener('click', function(event) {
  event.preventDefault(); // Mencegah default behavior (tidak mereload halaman)
  
  // Mengambil elemen induk dropdown
  var dropdown = this.closest('.dropdown');

  // Toggle kelas 'active' pada elemen dropdown
  dropdown.classList.toggle('active');
});

// Menutup dropdown jika user mengklik di luar dropdown
window.addEventListener('click', function(event) {
  var dropdown = document.querySelector('.dropdown');
  if (!dropdown.contains(event.target)) {
      dropdown.classList.remove('active');
  }
});

// document.querySelector('#month-dropdown').addEventListener('click', () => {
//   const month = document.querySelector('#month').value;
//   fetch(`fetch-data.php?month=${month}`)
//       .then(response => response.json())
//       .then(data => {
//           // Update summary cards
//           document.querySelector('#pr-ok-total').textContent = data.pr_ok;
//           document.querySelector('#rfq-total').textContent = data.rfq;
//           document.querySelector('#gr-total').textContent = data.gr;

//           // Update not-input section
//           const notInputTotal = data.not_input.length;
//           document.querySelector('#not-input-total').textContent = notInputTotal;

//           const notInputList = document.querySelector('#not-input-list');
//           notInputList.innerHTML = '';
//           data.not_input.forEach(item => {
//               const li = document.createElement('li');
//               li.textContent = item.name; // Assuming item has a "name" field
//               notInputList.appendChild(li);
//           });
//       })
//       .catch(error => console.error('Error fetching data:', error));
// });
document.addEventListener("DOMContentLoaded", () => {
  const monthDropdown = document.getElementById("month");
  const prOkEl = document.getElementById("pr-ok");
  const rfqEl = document.getElementById("rfq");
  const grEl = document.getElementById("gr");
  const notInputEl = document.getElementById("not-input");

  const fetchData = (month) => {
      fetch(`fetch-data.php?month=${month}`)
          .then((response) => response.json())
          .then((data) => {
              if (data.status === "success") {
                  prOkEl.textContent = data.data.PR || 0;
                  rfqEl.textContent = data.data.RFQ || 0;
                  grEl.textContent = data.data.GR || 0;

                  const notInputItems = data.data.not_input;
                  notInputEl.textContent = notInputItems.length > 0
                      ? notInputItems.join(", ")
                      : "None";
              } else {
                  alert("Failed to fetch data.");
              }
          })
          .catch((error) => {
              console.error("Error:", error);
              alert("An error occurred while fetching data.");
          });
  };

 document.addEventListener("DOMContentLoaded", () => {
  const monthDropdown = document.getElementById("monthDropdown");
  if (monthDropdown) {
    monthDropdown.addEventListener("change", () => {
        const selectedMonth = monthDropdown.value;
        fetchData(selectedMonth);
    });
} else {
    console.error("Element with ID 'monthDropdown' not found.");
}

  fetchData(monthDropdown.value);
});
});


function populateDashboard(data) {
    // Populate summary cards
    document.getElementById("pr-total").innerText = data.totals.PR || 0;
    document.getElementById("rfq-total").innerText = data.totals.RFQ || 0;
    document.getElementById("gr-total").innerText = data.totals.GR || 0;
    document.getElementById("ph-total").innerText = data.totals.PH || 0;

    // Populate not-input items
    const notInputList = document.getElementById("not-input-list");
    notInputList.innerHTML = "";
    data.not_input_items.forEach((item) => {
        const li = document.createElement("li");
        li.textContent = item;
        notInputList.appendChild(li);
    });
}