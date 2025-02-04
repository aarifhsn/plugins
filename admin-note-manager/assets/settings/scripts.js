/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/js/main.scss":
/*!*****************************!*\
  !*** ./assets/js/main.scss ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!******************************!*\
  !*** ./assets/js/scripts.js ***!
  \******************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _main_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./main.scss */ "./assets/js/main.scss");

jQuery(document).ready(function ($) {
  // Toggle the notes panel.
  $("#admin-note-manager-icon").on("click", function () {
    const panel = $("#admin-note-manager-panel");
    if (panel.is(":visible")) {
      panel.slideUp();
    } else {
      // Fetch notes via AJAX.
      if (!panel.data("loaded")) {
        $.ajax({
          url: adminNoteManagerAjax.ajax_url,
          type: "POST",
          data: {
            action: "fetch_admin_notes",
            security: adminNoteManagerAjax.nonce
          },
          beforeSend: function () {
            panel.html("<p>Loading...</p>");
          },
          success: function (response) {
            if (response.success) {
              panel.html(response.data);
              panel.data("loaded", true);
            } else {
              panel.html("<p>No notes found.</p>");
            }
          },
          error: function () {
            panel.html("<p>Error loading notes.</p>");
          }
        });
      }
      panel.slideDown();
    }
  });
});
})();

/******/ })()
;
//# sourceMappingURL=scripts.js.map