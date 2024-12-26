document.addEventListener("DOMContentLoaded", () => {
    const itemDropdown = document.getElementById("item_id");
  
    // Fetch data items
    fetch("fetch-data.php?action=fetch_items")
      .then((response) => {
        if (!response.ok) {
          throw new Error("Failed to fetch items");
        }
        return response.json();
      })
      .then((data) => {
        // Isi dropdown dengan data
        if (Array.isArray(data) && data.length > 0) {
          data.forEach((item) => {
            const option = document.createElement("option");
            option.value = item.id;
            option.textContent = item.name;
            itemDropdown.appendChild(option);
          });
        } else {
          // Jika tidak ada data
          const option = document.createElement("option");
          option.value = "";
          option.textContent = "No items available";
          itemDropdown.appendChild(option);
        }
      })
      .catch((error) => {
        console.error("Error fetching items:", error);
        const option = document.createElement("option");
        option.value = "";
        option.textContent = "Error loading items";
        itemDropdown.appendChild(option);
      });
  });
  