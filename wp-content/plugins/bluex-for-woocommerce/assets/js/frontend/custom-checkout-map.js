// Listen for the DOM content to be loaded
document.addEventListener("DOMContentLoaded", () => {
  clearWooCommerceShippingCache(); // Clear the WooCommerce shipping cache
  handleMessagesFromWindow(); // Handle messages from the window
});

// Function to handle messages received by the window
function handleMessagesFromWindow() {
  window.addEventListener("message", (event) => {
    const elements = getDOMElements(); // Get DOM elements

    switch (event.data.type) {
      case "pudo:select":
        handlePudoSelect(event, elements); // Handle 'pudo:select' messages
        break;
      case "pudo:change":
        // some action for 'pudo:change' messages
        break;
    }
  });
}

// Function to get specific DOM elements
function getDOMElements() {
  return {
    inputState: document.getElementById("billing_state"), // Input for billing state
    inputDir: document.getElementById("billing_address_1"), // Input for billing address line 1
    inputDir2: document.getElementById("billing_address_2"), // Input for billing address line 2
    countryContainer: document.getElementById(
      "select2-billing_country-container"
    ), // Input for billing country
    stateContainer: document.querySelector("#select2-billing_state-container"), // Container for billing state
    inputCity: document.getElementById("billing_city"), // Input for billing city
    agencyIdInput: document.getElementById("agencyId"), // Input for agency ID
  };
}

// Function to handle 'pudo:select' event
function handlePudoSelect(event, elements) {
  const data = event.data.payload; // Payload of the event

  if (data && data.location) {
    const {
      street_name = "",
      street_number = "",
      city_name = "",
      country_name = "",
      state_name = "",
    } = data.location; // Destructure location data

    // Set values to elements based on the event data
    elements.agencyIdInput.value = data.agency_id;
    elements.inputDir.value = `${street_name} ${street_number}`;
    elements.inputDir2.value = data.agency_name;
    if (elements.countryContainer)
      elements.countryContainer.value = country_name;

    const state = getStateDetails(state_name); // Get state details
    elements.stateContainer.innerHTML = state.fullName; // Set the state's full name
    elements.inputState.value = state.abreviation; // Set the state's abbreviation
    elements.inputState.title = state.fullName; // Set the state's full name as title
    cityToInput(elements.inputCity, city_name); // Set the city name to the input

    triggerUpdateCheckout(); // Trigger checkout update
  }
}

// Function to set city name to the input field
function cityToInput(citybox, city_name) {
  // Check if the element is an input
  if (citybox.tagName === "INPUT") {
    citybox.value = city_name; // Set the city name
    return;
  }

  // Get attributes from the element
  var input_name = citybox.getAttribute("name");
  var input_id = citybox.getAttribute("id");
  var placeholder = citybox.getAttribute("placeholder");

  // Remove the select2 container if it exists
  var select2Container = citybox.parentNode.querySelector(".select2-container");
  if (select2Container) {
    select2Container.parentNode.removeChild(select2Container);
  }

  // Create a new input element and configure it
  var newInput = document.createElement("input");
  newInput.type = "text";
  newInput.className = "input-text";
  newInput.name = input_name;
  newInput.id = input_id;
  newInput.placeholder = placeholder;
  newInput.value = city_name;

  // Replace the original element with the new input
  citybox.parentNode.replaceChild(newInput, citybox);
}

// Function to trigger a change event
function triggerChangeEvent(inputElement) {
  // Dispatch the change event
  let event = new Event("change", {
    bubbles: true,
    cancelable: true,
  });
  inputElement.dispatchEvent(event);
}

// Function to get state details based on name
function getStateDetails(name) {
  const states = [
    {
      abreviation: "CL-AI",
      fullName: "Aisén del General Carlos Ibañez del Campo",
      nameFromIframe: "Aysén",
    },
    {
      abreviation: "CL-AN",
      fullName: "Antofagasta",
      nameFromIframe: "Antofagasta",
    },
    {
      abreviation: "CL-AP",
      fullName: "Arica y Parinacota",
      nameFromIframe: "Arica y Parinacota",
    },
    {
      abreviation: "CL-AR",
      fullName: "La Araucanía",
      nameFromIframe: "Araucanía",
    },
    { abreviation: "CL-AT", fullName: "Atacama", nameFromIframe: "Atacama" },
    { abreviation: "CL-BI", fullName: "Biobío", nameFromIframe: "Bío - Bío" },
    { abreviation: "CL-CO", fullName: "Coquimbo", nameFromIframe: "Coquimbo" },
    {
      abreviation: "CL-LI",
      fullName: "Libertador General Bernardo O'Higgins",
      nameFromIframe: "Libertador General Bernardo O`Higgins",
    },
    {
      abreviation: "CL-LL",
      fullName: "Los Lagos",
      nameFromIframe: "Los Lagos",
    },
    { abreviation: "CL-LR", fullName: "Los Ríos", nameFromIframe: "Los Ríos" },
    {
      abreviation: "CL-MA",
      fullName: "Magallanes",
      nameFromIframe: "Magallanes y la Antartica Chilena",
    },
    { abreviation: "CL-ML", fullName: "Maule", nameFromIframe: "Maule" },
    { abreviation: "CL-NB", fullName: "Ñuble", nameFromIframe: "Ñuble" },
    {
      abreviation: "CL-RM",
      fullName: "Región Metropolitana de Santiago",
      nameFromIframe: "Metropolitana de Santiago",
    },
    { abreviation: "CL-TA", fullName: "Tarapacá", nameFromIframe: "Tarapacá" },
    {
      abreviation: "CL-VS",
      fullName: "Valparaíso",
      nameFromIframe: "Valparaiso",
    },
    { abreviation: "", fullName: "", nameFromIframe: "" },
  ];

  return states.find((state) => state.nameFromIframe === name) || {};
}

// Function to select a shipping method
function selectShipping(shippingMethod) {
  const pudoIdInput = document.getElementById("isPudoSelected");
  pudoIdInput.value = shippingMethod; // Set the shipping method

  if (shippingMethod === "normalShipping") {
    const elements = getDOMElements();
    clearElements(elements); // Clear elements

    triggerChangeEvent(elements.inputState); // Trigger change event on state input
  }
  clearWooCommerceShippingCache(); // Clear WooCommerce shipping cache
}

// Function to clear input elements
function clearElements(elements) {
  // Clear values of elements
  elements.agencyIdInput.value = "";
  elements.inputDir.value = "";
  elements.inputDir2.value = "";
}

// Function to trigger update checkout event
function triggerUpdateCheckout() {
  const event = new CustomEvent("update_checkout");
  document.body.dispatchEvent(event); // Dispatch the event
}

// Function to clear WooCommerce shipping cache
function clearWooCommerceShippingCache() {
  const data = { action: "clear_shipping_cache" };
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "/wp-admin/admin-ajax.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.status === 200) {
      triggerUpdateCheckout(); // Trigger checkout update on successful request
    }
  };
  xhr.send(`action=${data.action}`); // Send the request
}
