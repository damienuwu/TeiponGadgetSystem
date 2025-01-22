const stateCityPostalData = {
  Johor: {
    "Johor Bahru": ["80000", "80100", "80150", "80200", "80300"],
    Mersing: ["86800", "86810"],
    "Kota Tinggi": ["81900", "81910"],
    Tebrau: ["81100", "81200"],
    "Pasir Gudang": ["81700", "81750"],
    Skudai: ["81300", "81310"],
    Kluang: ["86000", "86100"],
    "Batu Pahat": ["83000", "83100"],
    Muar: ["84000", "84100"],
    Segamat: ["85000", "85100"],
    Pontian: ["82000", "82010"],
    "Yong Peng": ["83700", "83710"],
  },
  Kedah: {
    "Alor Setar": ["05000", "05100", "05150"],
    "Sungai Petani": ["08000", "08010"],
    Kulim: ["09000", "09010"],
    Jitra: ["06000", "06100"],
    Langkawi: ["07000", "07100"],
    Baling: ["09100", "09110"],
    Pendang: ["06700", "06710"],
  },
  Kelantan: {
    "Kota Bharu": ["15000", "15100", "15200"],
    Tumpat: ["16200", "16210"],
    "Gua Musang": ["18300", "18310"],
    "Pasir Mas": ["17000", "17010"],
    Machang: ["18500", "18510"],
    "Tanah Merah": ["17500", "17510"],
  },
  Melaka: {
    "Bandaraya Melaka": ["75000", "75100", "75200"],
    "Ayer Keroh": ["75450", "75460"],
    "Alor Gajah": ["78000", "78100"],
    "Masjid Tanah": ["78300", "78310"],
    Jasin: ["77000", "77100"],
    "Bukit Katil": ["75400", "75430"],
  },
  "Negeri Sembilan": {
    Seremban: ["70000", "70100", "70200", "70300"],
    "Port Dickson": ["71000", "71010"],
    Nilai: ["71800", "71810"],
    Bahau: ["72100", "72110"],
    Tampin: ["73000", "73100"],
    "Kuala Pilah": ["72000", "72010"],
  },
  Pahang: {
    Kuantan: ["25000", "25100", "25200"],
    Temerloh: ["28000", "28100"],
    Bentong: ["28700", "28710"],
    Mentakab: ["28400", "28410"],
    Raub: ["27600", "27610"],
    Jerantut: ["27000", "27010"],
  },
  Perak: {
    Ipoh: ["30000", "30100", "30200", "30300"],
    Taiping: ["34000", "34100"],
    Sitiawan: ["32000", "32100"],
    "Teluk Intan": ["36000", "36010"],
    "Kuala Kangsar": ["33000", "33010"],
    "Sungai Siput": ["31100", "31200"],
  },
  Perlis: {
    Kangar: ["01000", "01500"],
    Arau: ["02600", "02610"],
    "Padang Besar": ["02100", "02110"],
    "Kuala Perlis": ["02000", "02100"],
  },
  "Pulau Pinang": {
    Georgetown: ["10000", "10100", "10200", "10300"],
    "Bukit Mertajam": ["14000", "14100"],
    Butterworth: ["12000", "12100"],
    "Bayan Lepas": ["11900", "11910", "11920"],
    "Tanjung Bungah": ["11200", "11300"],
    "Batu Ferringhi": ["11100", "11210"],
  },
  Sabah: {
    "Kota Kinabalu": ["88000", "88100", "88200"],
    Sandakan: ["90000", "90100"],
    Tawau: ["91000", "91100"],
    "Lahad Datu": ["91100", "91110"],
    Kudat: ["89050", "89150"],
    Ranau: ["89300", "89400"],
  },
  Sarawak: {
    Kuching: ["93000", "93100", "93200"],
    Miri: ["98000", "98100"],
    Sibu: ["96000", "96100"],
    Bintulu: ["97000", "97100"],
    Limbang: ["98700", "98750"],
    Sarikei: ["96100", "96200"],
  },
  Selangor: {
    "Shah Alam": ["40000", "40100", "40150"],
    "Petaling Jaya": ["46000", "46100", "46150"],
    "Subang Jaya": ["47500", "47600"],
    Klang: ["41000", "41100", "41200"],
    Ampang: ["68000", "68100"],
    Kajang: ["43000", "43100"],
    Rawang: ["48000", "48100"],
    Sepang: ["43900", "43950"],
  },
  Terengganu: {
    "Kuala Terengganu": ["20000", "20100", "20200"],
    Dungun: ["23000", "23100"],
    Kemaman: ["24000", "24100"],
    Marang: ["21600", "21610"],
    Kerteh: ["24300", "24310"],
    Jerteh: ["22000", "22100"],
  },
  "Wilayah Persekutuan": {
    "Kuala Lumpur": ["50000", "50100", "50200", "50300", "50400", "50500"],
    Putrajaya: ["62000", "62100", "62150", "62200", "62250", "62300"],
    Labuan: ["87000", "87010", "87020"],
  },
};

document.addEventListener("DOMContentLoaded", function () {
  const stateSelect = document.getElementById("state");
  const citySelect = document.getElementById("city");
  const postalCodeSelect = document.getElementById("postalCode");

  // Get saved values from PHP
  const savedState = stateSelect.getAttribute("data-saved-state");
  const savedCity = citySelect.getAttribute("data-saved-city");
  const savedPostalCode = postalCodeSelect.getAttribute("data-saved-postal");

  // Initialize state dropdown
  Object.keys(stateCityPostalData).forEach((state) => {
    const option = document.createElement("option");
    option.value = state;
    option.textContent = state;
    option.selected = state === savedState;
    stateSelect.appendChild(option);
  });

  // Function to update city dropdown
  function updateCities(state) {
    citySelect.innerHTML = '<option value="">Select City</option>';
    postalCodeSelect.innerHTML = '<option value="">Select Postal Code</option>';

    if (state && stateCityPostalData[state]) {
      Object.keys(stateCityPostalData[state]).forEach((city) => {
        const option = document.createElement("option");
        option.value = city;
        option.textContent = city;
        option.selected = city === savedCity;
        citySelect.appendChild(option);
      });

      // If we have a saved city, update postal codes
      if (savedCity && state === savedState) {
        updatePostalCodes(state, savedCity);
      }
    }
  }

  // Function to update postal codes dropdown
  function updatePostalCodes(state, city) {
    postalCodeSelect.innerHTML = '<option value="">Select Postal Code</option>';

    if (state && city && stateCityPostalData[state][city]) {
      stateCityPostalData[state][city].forEach((postalCode) => {
        const option = document.createElement("option");
        option.value = postalCode;
        option.textContent = postalCode;
        option.selected = postalCode === savedPostalCode;
        postalCodeSelect.appendChild(option);
      });
    }
  }

  // Event listeners
  stateSelect.addEventListener("change", function () {
    updateCities(this.value);
  });

  citySelect.addEventListener("change", function () {
    updatePostalCodes(stateSelect.value, this.value);
  });

  // Initialize dropdowns with saved values
  if (savedState) {
    updateCities(savedState);
  }
});
