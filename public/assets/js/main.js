"use strict";

(function () {
  var sidebarStorageKey = "adminHMD.sidebarMini";
  var themeStorageKey = "adminHMD.colorTheme";
  var desktopMedia = "(min-width: 992px)";

  function onReady(callback) {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", callback);
      return;
    }

    callback();
  }

  function isDesktop() {
    return window.matchMedia(desktopMedia).matches;
  }

  function canUseStorage() {
    try {
      var testKey = sidebarStorageKey + ".test";
      window.localStorage.setItem(testKey, "1");
      window.localStorage.removeItem(testKey);
      return true;
    } catch (error) {
      return false;
    }
  }

  function getSavedMiniState(storageAvailable) {
    if (!storageAvailable) {
      return false;
    }

    return window.localStorage.getItem(sidebarStorageKey) === "true";
  }

  function saveMiniState(storageAvailable, isMini) {
    if (storageAvailable) {
      window.localStorage.setItem(sidebarStorageKey, String(isMini));
    }
  }

  function getPreferredTheme(storageAvailable) {
    var savedTheme = storageAvailable ? window.localStorage.getItem(themeStorageKey) : "";

    if (savedTheme === "dark" || savedTheme === "light") {
      return savedTheme;
    }

    if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
      return "dark";
    }

    return "light";
  }

  onReady(function () {
    var body = document.body;
    var sidebarToggle = document.querySelector("[data-sidebar-toggle]");
    var themeToggles = document.querySelectorAll("[data-theme-toggle]");
    var themeIcons = document.querySelectorAll("[data-theme-icon]");
    var closeButtons = document.querySelectorAll("[data-sidebar-close]");
    var sidebarLinks = document.querySelectorAll(".sidebar-nav .nav-link");
    var mediaQuery = window.matchMedia(desktopMedia);
    var storageAvailable = canUseStorage();

    function initValidation() {
      var forms = document.querySelectorAll(".needs-validation");

      Array.prototype.forEach.call(forms, function (form) {
        form.addEventListener("submit", function (event) {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }

          form.classList.add("was-validated");
        });
      });
    }

    function initTableSearch() {
      var searchInputs = document.querySelectorAll("[data-table-search]");

      Array.prototype.forEach.call(searchInputs, function (input) {
        var tableId = input.getAttribute("data-table-search");
        var table = document.getElementById(tableId);

        if (!table) {
          return;
        }

        // Jika tabel menggunakan pagination universal, biarkan pagination yang menangani filter pencarian
        if (document.querySelector('[data-table-pagination="' + tableId + '"]')) {
          return;
        }

        input.addEventListener("input", function () {
          var query = input.value.trim().toLowerCase();
          var rows = table.querySelectorAll("tbody tr");

          Array.prototype.forEach.call(rows, function (row) {
            row.hidden = query !== "" && row.textContent.toLowerCase().indexOf(query) === -1;
          });
        });
      });
    }

    function initTablePagination() {
      var paginationFooters = document.querySelectorAll("[data-table-pagination]");

      Array.prototype.forEach.call(paginationFooters, function (footer) {
        var tableId = footer.getAttribute("data-table-pagination");
        var table = document.getElementById(tableId);

        if (!table) return;

        var tbody = table.querySelector("tbody");
        if (!tbody) return;

        var entriesSelect = document.querySelector('[data-table-entries="' + tableId + '"]');
        var searchInput = document.querySelector('[data-table-search="' + tableId + '"]');

        var infoEl = footer.querySelector(".table-pagination-info");
        if (!infoEl) {
          infoEl = document.createElement("p");
          infoEl.className = "table-pagination-info mb-0";
          footer.prepend(infoEl);
        }

        var navContainer = footer.querySelector(".pagination-container");
        if (!navContainer) {
          navContainer = document.createElement("div");
          navContainer.className = "pagination-container";
          footer.appendChild(navContainer);
        }

        // Ambil semua baris data (dukung baris accordion/collapse anak)
        var rawRows = Array.prototype.slice.call(tbody.querySelectorAll("tr"));
        var parentRows = [];

        rawRows.forEach(function (tr) {
          // Abaikan baris pesan kosong jika ada baris data riil
          if (tr.querySelector("td[colspan]") && rawRows.length > 1) {
            return;
          }
          if (tr.classList.contains("collapse")) {
            if (parentRows.length > 0) {
              parentRows[parentRows.length - 1].childRow = tr;
            }
          } else {
            parentRows.push({
              tr: tr,
              text: tr.textContent.toLowerCase(),
              childRow: null
            });
          }
        });

        var state = {
          currentPage: 1,
          perPage: entriesSelect ? parseInt(entriesSelect.value, 10) || 10 : 10,
          searchQuery: searchInput ? searchInput.value.trim().toLowerCase() : ""
        };

        function render() {
          var filtered = parentRows.filter(function (item) {
            if (!state.searchQuery) return true;
            var match = item.text.indexOf(state.searchQuery) !== -1;
            if (!match && item.childRow) {
              match = item.childRow.textContent.toLowerCase().indexOf(state.searchQuery) !== -1;
            }
            return match;
          });

          var total = filtered.length;
          var totalPages = Math.max(1, Math.ceil(total / state.perPage));
          if (state.currentPage > totalPages) state.currentPage = totalPages;
          if (state.currentPage < 1) state.currentPage = 1;

          var startIdx = (state.currentPage - 1) * state.perPage;
          var endIdx = Math.min(startIdx + state.perPage, total);

          parentRows.forEach(function (item) {
            item.tr.hidden = true;
            item.tr.style.display = "none";
            if (item.childRow) {
              item.childRow.hidden = true;
            }
          });

          for (var i = startIdx; i < endIdx; i++) {
            if (filtered[i]) {
              filtered[i].tr.hidden = false;
              filtered[i].tr.style.display = "";
              if (filtered[i].childRow) {
                filtered[i].childRow.hidden = false;
              }
            }
          }

          var emptyMsgRow = tbody.querySelector(".table-pagination-empty-row");
          if (total === 0 && parentRows.length > 0) {
            if (!emptyMsgRow) {
              emptyMsgRow = document.createElement("tr");
              emptyMsgRow.className = "table-pagination-empty-row";
              emptyMsgRow.innerHTML = '<td colspan="100%" class="text-center py-4 text-muted"><i class="bi bi-search fs-4 d-block mb-1 opacity-50"></i> Tidak ada data yang cocok dengan pencarian</td>';
              tbody.appendChild(emptyMsgRow);
            } else {
              emptyMsgRow.hidden = false;
              emptyMsgRow.style.display = "";
            }
          } else if (emptyMsgRow) {
            emptyMsgRow.hidden = true;
            emptyMsgRow.style.display = "none";
          }

          var displayStart = total === 0 ? 0 : startIdx + 1;
          var infoHtml = "Showing <strong>" + displayStart + "</strong> to <strong>" + endIdx + "</strong> of <strong>" + total + "</strong> entries";
          if (parentRows.length > total && state.searchQuery) {
            infoHtml += ' <span class="text-muted small">(filtered from ' + parentRows.length + ' total)</span>';
          }
          infoEl.innerHTML = infoHtml;

          renderNav(totalPages);

          table.dispatchEvent(new CustomEvent("tablePageChanged", { detail: { currentPage: state.currentPage, total: total } }));
        }

        function renderNav(totalPages) {
          var ul = document.createElement("ul");
          ul.className = "pagination-minimalist";

          // Tombol Previous
          var prevLi = document.createElement("li");
          prevLi.className = "page-item nav-btn" + (state.currentPage <= 1 ? " disabled" : "");
          var prevBtn = document.createElement("button");
          prevBtn.type = "button";
          prevBtn.className = "page-link";
          prevBtn.innerHTML = '<i class="bi bi-chevron-left" aria-hidden="true"></i> Previous';
          if (state.currentPage <= 1) prevBtn.disabled = true;
          prevBtn.addEventListener("click", function () {
            if (state.currentPage > 1) {
              state.currentPage--;
              render();
            }
          });
          prevLi.appendChild(prevBtn);
          ul.appendChild(prevLi);

          // Nomor halaman dengan ellipsis
          var pages = [];
          if (totalPages <= 7) {
            for (var p = 1; p <= totalPages; p++) pages.push(p);
          } else {
            pages.push(1);
            if (state.currentPage > 3) pages.push("...");
            var startP = Math.max(2, state.currentPage - 1);
            var endP = Math.min(totalPages - 1, state.currentPage + 1);
            for (var p = startP; p <= endP; p++) pages.push(p);
            if (state.currentPage < totalPages - 2) pages.push("...");
            pages.push(totalPages);
          }

          pages.forEach(function (p) {
            var li = document.createElement("li");
            if (p === "...") {
              li.className = "page-item disabled";
              li.innerHTML = '<span class="page-link">...</span>';
            } else {
              li.className = "page-item" + (p === state.currentPage ? " active" : "");
              var btn = document.createElement("button");
              btn.type = "button";
              btn.className = "page-link";
              btn.textContent = p;
              btn.addEventListener("click", function () {
                state.currentPage = p;
                render();
              });
              li.appendChild(btn);
            }
            ul.appendChild(li);
          });

          // Tombol Next
          var nextLi = document.createElement("li");
          nextLi.className = "page-item nav-btn" + (state.currentPage >= totalPages ? " disabled" : "");
          var nextBtn = document.createElement("button");
          nextBtn.type = "button";
          nextBtn.className = "page-link";
          nextBtn.innerHTML = 'Next <i class="bi bi-chevron-right" aria-hidden="true"></i>';
          if (state.currentPage >= totalPages) nextBtn.disabled = true;
          nextBtn.addEventListener("click", function () {
            if (state.currentPage < totalPages) {
              state.currentPage++;
              render();
            }
          });
          nextLi.appendChild(nextBtn);
          ul.appendChild(nextLi);

          navContainer.innerHTML = "";
          navContainer.appendChild(ul);
        }

        if (entriesSelect) {
          entriesSelect.addEventListener("change", function (e) {
            state.perPage = parseInt(e.target.value, 10) || 10;
            state.currentPage = 1;
            render();
          });
        }

        if (searchInput) {
          searchInput.addEventListener("input", function (e) {
            state.searchQuery = e.target.value.trim().toLowerCase();
            state.currentPage = 1;
            render();
          });
        }

        render();
      });
    }

    function updateThemeControls(theme) {
      var nextTheme = theme === "dark" ? "light" : "dark";
      var label = "Switch to " + nextTheme + " mode";
      var iconClass = theme === "dark" ? "bi bi-sun" : "bi bi-moon-stars";

      Array.prototype.forEach.call(themeToggles, function (button) {
        button.setAttribute("aria-label", label);
        button.setAttribute("title", label);
      });

      Array.prototype.forEach.call(themeIcons, function (icon) {
        icon.className = iconClass;
      });
    }

    function applyTheme(theme) {
      document.documentElement.setAttribute("data-theme", theme);
      document.documentElement.setAttribute("data-bs-theme", theme);

      if (storageAvailable) {
        window.localStorage.setItem(themeStorageKey, theme);
      }

      updateThemeControls(theme);
    }

    function initThemeToggle() {
      applyTheme(getPreferredTheme(storageAvailable));

      Array.prototype.forEach.call(themeToggles, function (button) {
        button.addEventListener("click", function () {
          var currentTheme = document.documentElement.getAttribute("data-theme") === "dark" ? "dark" : "light";
          applyTheme(currentTheme === "dark" ? "light" : "dark");
        });
      });
    }

    function initSearchableSelect() {
      var selects = document.querySelectorAll("select.select-searchable, select[data-searchable]");

      Array.prototype.forEach.call(selects, function (select) {
        if (select.dataset.searchableInitialized === "true") {
          return;
        }
        select.dataset.searchableInitialized = "true";

        // Hide original select visually while keeping it fully functional in DOM
        select.classList.add("searchable-select-hidden");

        var placeholder = select.getAttribute("data-placeholder") || "-- Pilih / Cari Manager --";

        // Wrapper container
        var wrapper = document.createElement("div");
        wrapper.className = "searchable-select-wrapper";

        // Toggle button
        var toggle = document.createElement("div");
        toggle.className = "searchable-select-toggle";
        toggle.tabIndex = 0;
        toggle.setAttribute("role", "combobox");
        toggle.setAttribute("aria-expanded", "false");

        var labelWrapper = document.createElement("div");
        labelWrapper.className = "searchable-select-label-wrapper";

        var personIcon = document.createElement("i");
        personIcon.className = "bi bi-person text-muted";

        var label = document.createElement("span");
        label.className = "searchable-select-label";

        labelWrapper.appendChild(personIcon);
        labelWrapper.appendChild(label);

        var actions = document.createElement("div");
        actions.className = "searchable-select-actions";

        var clearBtn = document.createElement("button");
        clearBtn.type = "button";
        clearBtn.className = "searchable-select-clear";
        clearBtn.title = "Hapus pilihan";
        clearBtn.innerHTML = '<i class="bi bi-x"></i>';
        clearBtn.style.display = "none";

        var arrowIcon = document.createElement("i");
        arrowIcon.className = "bi bi-chevron-down searchable-select-arrow";

        actions.appendChild(clearBtn);
        actions.appendChild(arrowIcon);

        toggle.appendChild(labelWrapper);
        toggle.appendChild(actions);

        // Dropdown panel
        var dropdown = document.createElement("div");
        dropdown.className = "searchable-select-dropdown";

        // Search input box
        var searchBox = document.createElement("div");
        searchBox.className = "searchable-select-search-box";

        var searchIcon = document.createElement("i");
        searchIcon.className = "bi bi-search searchable-select-search-icon";

        var searchInput = document.createElement("input");
        searchInput.type = "text";
        searchInput.className = "searchable-select-input";
        searchInput.placeholder = select.getAttribute("data-search-placeholder") || "Ketik nama atau NPK manager...";
        searchInput.autocomplete = "off";

        var searchClearBtn = document.createElement("button");
        searchClearBtn.type = "button";
        searchClearBtn.className = "searchable-select-input-clear";
        searchClearBtn.title = "Reset pencarian";
        searchClearBtn.style.display = "none";
        searchClearBtn.innerHTML = '<i class="bi bi-x-circle-fill"></i>';

        searchBox.appendChild(searchIcon);
        searchBox.appendChild(searchInput);
        searchBox.appendChild(searchClearBtn);

        // Meta bar
        var metaBar = document.createElement("div");
        metaBar.className = "searchable-select-meta";
        var countSpan = document.createElement("span");
        countSpan.className = "searchable-count";
        var hintSpan = document.createElement("span");
        hintSpan.className = "text-muted";
        hintSpan.textContent = "Esc untuk tutup";
        metaBar.appendChild(countSpan);
        metaBar.appendChild(hintSpan);

        // Scrollable menu list
        var menu = document.createElement("div");
        menu.className = "searchable-select-menu";
        menu.setAttribute("role", "listbox");

        // No results empty state
        var noResults = document.createElement("div");
        noResults.className = "searchable-select-no-results";
        noResults.style.display = "none";
        noResults.innerHTML = '<i class="bi bi-person-x"></i><div>Tidak ada data yang cocok</div>';

        dropdown.appendChild(searchBox);
        dropdown.appendChild(metaBar);
        dropdown.appendChild(menu);
        dropdown.appendChild(noResults);

        wrapper.appendChild(toggle);
        wrapper.appendChild(dropdown);

        select.parentNode.insertBefore(wrapper, select.nextSibling);

        function renderItems() {
          menu.innerHTML = "";
          var options = select.options;
          var totalCount = 0;

          for (var i = 0; i < options.length; i++) {
            var opt = options[i];
            var item = document.createElement("div");
            item.className = "searchable-select-item";
            item.setAttribute("role", "option");
            item.dataset.value = opt.value;
            item.dataset.text = opt.text;

            var rawText = opt.text.trim();
            var npkMatch = rawText.match(/^(.*?)\s*\(NPK:\s*([^)]+)\)$/i);

            if (!opt.value) {
              item.classList.add("is-empty-option");
              item.innerHTML = '<div class="item-content"><span class="item-title text-muted">' + rawText + '</span></div><i class="bi bi-check2 check-icon"></i>';
              item.dataset.search = rawText.toLowerCase();
            } else if (npkMatch) {
              totalCount++;
              var name = npkMatch[1].trim();
              var npk = npkMatch[2].trim();
              item.innerHTML = '<div class="item-content"><span class="item-title">' + name + '</span><span class="item-subtitle"><i class="bi bi-person-badge me-1"></i>NPK: ' + npk + '</span></div><i class="bi bi-check2 check-icon"></i>';
              item.dataset.search = (name + " " + npk).toLowerCase();
            } else {
              totalCount++;
              item.innerHTML = '<div class="item-content"><span class="item-title">' + rawText + '</span></div><i class="bi bi-check2 check-icon"></i>';
              item.dataset.search = rawText.toLowerCase();
            }

            if (opt.selected) {
              item.classList.add("is-selected");
            }

            (function (optVal, optEl) {
              optEl.addEventListener("click", function (e) {
                e.stopPropagation();
                selectValue(optVal);
                closeDropdown();
                toggle.focus();
              });
            })(opt.value, item);

            menu.appendChild(item);
          }

          countSpan.textContent = totalCount + " manager tersedia";
        }

        function updateDisplay() {
          var selectedOpt = select.options[select.selectedIndex];
          if (selectedOpt && selectedOpt.value !== "") {
            label.textContent = selectedOpt.text;
            label.classList.remove("is-placeholder");
            clearBtn.style.display = "inline-flex";
          } else if (selectedOpt && selectedOpt.value === "") {
            label.textContent = selectedOpt.text || placeholder;
            label.classList.add("is-placeholder");
            clearBtn.style.display = "none";
          } else {
            label.textContent = placeholder;
            label.classList.add("is-placeholder");
            clearBtn.style.display = "none";
          }

          var items = menu.querySelectorAll(".searchable-select-item");
          for (var i = 0; i < items.length; i++) {
            if (items[i].dataset.value === select.value) {
              items[i].classList.add("is-selected");
            } else {
              items[i].classList.remove("is-selected");
            }
          }
        }

        function selectValue(val) {
          if (select.value !== val) {
            select.value = val;
            var event = new Event("change", { bubbles: true });
            select.dispatchEvent(event);
          }
          updateDisplay();
        }

        function openDropdown() {
          var openDropdowns = document.querySelectorAll(".searchable-select-dropdown.is-open");
          Array.prototype.forEach.call(openDropdowns, function (d) {
            if (d !== dropdown) {
              d.classList.remove("is-open");
              var pToggle = d.parentNode.querySelector(".searchable-select-toggle");
              if (pToggle) pToggle.classList.remove("is-open");
            }
          });

          dropdown.classList.add("is-open");
          toggle.classList.add("is-open");
          toggle.setAttribute("aria-expanded", "true");

          searchInput.value = "";
          searchClearBtn.style.display = "none";
          filterOptions("");

          setTimeout(function () {
            searchInput.focus();
          }, 50);

          var selectedItem = menu.querySelector(".searchable-select-item.is-selected");
          if (selectedItem) {
            selectedItem.scrollIntoView({ block: "nearest" });
          }
        }

        function closeDropdown() {
          dropdown.classList.remove("is-open");
          toggle.classList.remove("is-open");
          toggle.setAttribute("aria-expanded", "false");
          var focused = menu.querySelector(".is-focused");
          if (focused) focused.classList.remove("is-focused");
        }

        function filterOptions(query) {
          var q = query.trim().toLowerCase();
          var items = menu.querySelectorAll(".searchable-select-item");
          var visibleCount = 0;

          for (var i = 0; i < items.length; i++) {
            var it = items[i];
            var searchTarget = it.dataset.search || it.textContent.toLowerCase();
            if (!q || searchTarget.indexOf(q) !== -1 || it.classList.contains("is-empty-option")) {
              it.style.display = "flex";
              if (!it.classList.contains("is-empty-option")) visibleCount++;
            } else {
              it.style.display = "none";
            }
          }

          if (q) {
            countSpan.textContent = visibleCount + " cocok";
            noResults.style.display = (visibleCount === 0 && !menu.querySelector('.is-empty-option[style*="flex"]')) ? "block" : "none";
            searchClearBtn.style.display = "inline-flex";
          } else {
            var total = menu.querySelectorAll(".searchable-select-item:not(.is-empty-option)").length;
            countSpan.textContent = total + " manager tersedia";
            noResults.style.display = "none";
            searchClearBtn.style.display = "none";
          }
        }

        toggle.addEventListener("click", function (e) {
          if (e.target.closest(".searchable-select-clear")) {
            return;
          }
          if (dropdown.classList.contains("is-open")) {
            closeDropdown();
          } else {
            openDropdown();
          }
        });

        toggle.addEventListener("keydown", function (e) {
          if (e.key === "Enter" || e.key === " " || e.key === "ArrowDown") {
            e.preventDefault();
            openDropdown();
          }
        });

        clearBtn.addEventListener("click", function (e) {
          e.stopPropagation();
          selectValue("");
          closeDropdown();
          toggle.focus();
        });

        searchInput.addEventListener("input", function () {
          filterOptions(this.value);
        });

        searchClearBtn.addEventListener("click", function () {
          searchInput.value = "";
          filterOptions("");
          searchInput.focus();
        });

        searchInput.addEventListener("keydown", function (e) {
          var visibleItems = menu.querySelectorAll('.searchable-select-item:not([style*="display: none"])');
          if (!visibleItems.length) {
            if (e.key === "Escape") {
              closeDropdown();
              toggle.focus();
            }
            return;
          }

          var currentFocused = menu.querySelector(".searchable-select-item.is-focused");
          var currentIndex = -1;
          for (var i = 0; i < visibleItems.length; i++) {
            if (visibleItems[i] === currentFocused) {
              currentIndex = i;
              break;
            }
          }

          if (e.key === "ArrowDown") {
            e.preventDefault();
            var nextIndex = currentIndex < visibleItems.length - 1 ? currentIndex + 1 : 0;
            if (currentFocused) currentFocused.classList.remove("is-focused");
            visibleItems[nextIndex].classList.add("is-focused");
            visibleItems[nextIndex].scrollIntoView({ block: "nearest" });
          } else if (e.key === "ArrowUp") {
            e.preventDefault();
            var prevIndex = currentIndex > 0 ? currentIndex - 1 : visibleItems.length - 1;
            if (currentFocused) currentFocused.classList.remove("is-focused");
            visibleItems[prevIndex].classList.add("is-focused");
            visibleItems[prevIndex].scrollIntoView({ block: "nearest" });
          } else if (e.key === "Enter") {
            e.preventDefault();
            if (currentFocused) {
              selectValue(currentFocused.dataset.value);
              closeDropdown();
              toggle.focus();
            } else if (visibleItems.length === 1) {
              selectValue(visibleItems[0].dataset.value);
              closeDropdown();
              toggle.focus();
            }
          } else if (e.key === "Escape") {
            e.preventDefault();
            closeDropdown();
            toggle.focus();
          }
        });

        document.addEventListener("click", function (e) {
          if (!wrapper.contains(e.target)) {
            closeDropdown();
          }
        });

        select.addEventListener("change", updateDisplay);

        renderItems();
        updateDisplay();
      });
    }

    function convertTerbilang(num) {
      num = Math.floor(Math.abs(Number(num) || 0));
      if (num <= 0) return 'Nol Rupiah';

      var words = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

      function toWords(n) {
        if (n < 12) {
          return words[n];
        } else if (n < 20) {
          return words[n - 10] + ' Belas';
        } else if (n < 100) {
          return words[Math.floor(n / 10)] + ' Puluh ' + toWords(n % 10);
        } else if (n < 200) {
          return 'Seratus ' + toWords(n - 100);
        } else if (n < 1000) {
          return words[Math.floor(n / 100)] + ' Ratus ' + toWords(n % 100);
        } else if (n < 2000) {
          return 'Seribu ' + toWords(n - 1000);
        } else if (n < 1000000) {
          return toWords(Math.floor(n / 1000)) + ' Ribu ' + toWords(n % 1000);
        } else if (n < 1000000000) {
          return toWords(Math.floor(n / 1000000)) + ' Juta ' + toWords(n % 1000000);
        } else if (n < 1000000000000) {
          return toWords(Math.floor(n / 1000000000)) + ' Miliar ' + toWords(n % 1000000000);
        } else if (n < 1000000000000000) {
          return toWords(Math.floor(n / 1000000000000)) + ' Triliun ' + toWords(n % 1000000000000);
        }
        return '';
      }

      var result = toWords(num).replace(/\s+/g, ' ').trim();
      return result ? result + ' Rupiah' : 'Nol Rupiah';
    }

    window.initSearchableSelect = initSearchableSelect;
    window.convertTerbilang = convertTerbilang;

    initValidation();
    initTableSearch();
    initTablePagination();
    initThemeToggle();
    initSearchableSelect();

    // Initialize user profile values in UI. Provide a window.adminHMDUser object to override defaults.
    function initUserProfile() {
      if (!window.adminHMDUser) {
        return;
      }
      var user = window.adminHMDUser;

      var sidebarNameEl = document.querySelector(".sidebar-user strong");
      var sidebarWorkspaceEl = document.querySelector(".sidebar-user small");
      var sidebarAvatar = document.querySelector(".sidebar-user .avatar-img");
      var profileNameEls = document.querySelectorAll(".profile-name");
      var profileAvatarEls = document.querySelectorAll(".profile-button .avatar-img, .profile-button img");

      if (sidebarNameEl) sidebarNameEl.textContent = user.name;
      if (sidebarWorkspaceEl) sidebarWorkspaceEl.textContent = user.workspace;
      if (sidebarAvatar && user.avatar) { sidebarAvatar.src = user.avatar; sidebarAvatar.alt = user.name; }

      Array.prototype.forEach.call(profileNameEls, function (el) { el.textContent = user.name; });
      Array.prototype.forEach.call(profileAvatarEls, function (img) { if (user.avatar) img.src = user.avatar; if (user.name) img.alt = user.name; });
    }

    initUserProfile();

    if (!sidebarToggle) {
      return;
    }

    function setClass(element, className, enabled) {
      if (enabled) {
        element.classList.add(className);
      } else {
        element.classList.remove(className);
      }
    }

    function setToggleExpanded() {
      var expanded = isDesktop()
        ? !body.classList.contains("sidebar-mini")
        : body.classList.contains("sidebar-open");

      sidebarToggle.setAttribute("aria-expanded", String(expanded));
    }

    function closeMobileSidebar() {
      body.classList.remove("sidebar-open");
      setToggleExpanded();
    }

    function toggleSidebar() {
      if (isDesktop()) {
        body.classList.toggle("sidebar-mini");
        saveMiniState(storageAvailable, body.classList.contains("sidebar-mini"));
      } else {
        body.classList.toggle("sidebar-open");
      }

      setToggleExpanded();
    }

    function addCloseHandlers(items) {
      Array.prototype.forEach.call(items, function (item) {
        item.addEventListener("click", function () {
          if (item.getAttribute("data-bs-toggle") === "collapse" || item.closest('[data-bs-toggle="collapse"]')) {
            return;
          }
          if (!isDesktop()) {
            closeMobileSidebar();
          }
        });
      });
    }

    if (getSavedMiniState(storageAvailable) && isDesktop()) {
      body.classList.add("sidebar-mini");
    }

    sidebarToggle.addEventListener("click", toggleSidebar);
    addCloseHandlers(closeButtons);
    addCloseHandlers(sidebarLinks);
    setToggleExpanded();

    function handleBreakpointChange() {
      if (isDesktop()) {
        body.classList.remove("sidebar-open");
        setClass(body, "sidebar-mini", getSavedMiniState(storageAvailable));
      } else {
        body.classList.remove("sidebar-mini");
      }

      setToggleExpanded();
    }

    if (mediaQuery.addEventListener) {
      mediaQuery.addEventListener("change", handleBreakpointChange);
    } else if (mediaQuery.addListener) {
      mediaQuery.addListener(handleBreakpointChange);
    }
  });
})();
