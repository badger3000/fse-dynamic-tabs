document.addEventListener("DOMContentLoaded", function () {
  // Get all tab buttons (targeting the anchor elements inside button blocks with fse-tab-button class)
  const tabButtons = document.querySelectorAll(
    ".fse-tab-button .wp-block-button__link"
  );

  // Get the content container
  const contentContainer = document.getElementById("fse-dynamic-content");

  // If elements don't exist on the page, exit early
  if (!tabButtons.length || !contentContainer) {
    console.log("Tab buttons or content container not found");
    return;
  }

  // Function to show loading state
  function showLoading() {
    contentContainer.classList.add("loading");
    contentContainer.innerHTML = "<p>Loading content...</p>";
  }

  // Function to hide loading state
  function hideLoading() {
    contentContainer.classList.remove("loading");
  }

  // Function to set active tab
  function setActiveTab(activeButton) {
    // Remove active class from all button parent elements
    tabButtons.forEach((btn) => {
      btn.closest(".fse-tab-button").classList.remove("is-active");
    });

    // Add active class to clicked button's parent
    activeButton.closest(".fse-tab-button").classList.add("is-active");
  }

  // Function to determine tab ID from button's parent classes
  function getTabIdFromClasses(button) {
    const parentElement = button.closest(".fse-tab-button");
    if (!parentElement) return null;

    // Check for specific tab classes
    if (parentElement.classList.contains("products-tab")) return "products";
    if (parentElement.classList.contains("services-tab")) return "services";
    if (parentElement.classList.contains("about-tab")) return "about";

    // Fallback to text content
    const buttonText = button.textContent.trim().toLowerCase();
    if (buttonText.includes("product")) return "products";
    if (buttonText.includes("service")) return "services";
    if (buttonText.includes("about")) return "about";

    return null;
  }

  // Function to load tab content using admin-ajax.php
  function loadTabContent(tabId) {
    console.log("Loading content for tab ID:", tabId);
    showLoading();

    // Create form data for the request
    const formData = new FormData();
    formData.append("action", "get_tab_content");
    formData.append("tab_id", tabId);
    formData.append("nonce", fseDynamicTabs.nonce);

    // Use the Fetch API to get content
    fetch(fseDynamicTabs.ajax_url, {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        console.log("Response received:", data);

        if (data.success) {
          // Update the content with a smooth transition
          contentContainer.style.opacity = "0";

          setTimeout(() => {
            contentContainer.innerHTML = data.data.content;
            contentContainer.style.opacity = "1";
            hideLoading();

            // Update URL without page refresh (optional)
            const url = new URL(window.location);
            url.searchParams.set("tab", tabId);
            window.history.pushState({}, "", url);
          }, 200);
        } else {
          contentContainer.innerHTML = "<p>Error loading content</p>";
          hideLoading();
          console.error("Error in AJAX response:", data);
        }
      })
      .catch((error) => {
        console.error("Error fetching tab content:", error);
        contentContainer.innerHTML =
          "<p>Error loading content. Please try again.</p>";
        hideLoading();
      });
  }

  // Add click event listeners to all tab buttons
  tabButtons.forEach((button) => {
    button.addEventListener("click", function (event) {
      event.preventDefault();

      // Get tab ID from class instead of data attribute
      const tabId = getTabIdFromClasses(this);
      console.log("Tab ID from classes:", tabId);

      if (tabId) {
        // Set this as the active tab
        setActiveTab(this);

        // Load the content for this tab
        loadTabContent(tabId);
      } else {
        console.error("Could not determine tab ID");
      }
    });
  });

  // Check for tab parameter in URL on page load
  const urlParams = new URLSearchParams(window.location.search);
  const tabParam = urlParams.get("tab");

  if (tabParam) {
    // Find the button for the specified tab
    let matchingButton = null;

    if (tabParam === "products") {
      matchingButton = document.querySelector(
        ".products-tab .wp-block-button__link"
      );
    } else if (tabParam === "services") {
      matchingButton = document.querySelector(
        ".services-tab .wp-block-button__link"
      );
    } else if (tabParam === "about") {
      matchingButton = document.querySelector(
        ".about-tab .wp-block-button__link"
      );
    }

    if (matchingButton) {
      // Simulate a click on the matching button
      matchingButton.click();
    } else {
      // Default to first tab if no match
      tabButtons[0].click();
    }
  } else {
    // Default to first tab if no parameter
    tabButtons[0].click();
  }
});
