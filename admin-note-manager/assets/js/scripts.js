import "./main.scss";

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
            security: adminNoteManagerAjax.nonce,
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
          },
        });
      }
      panel.slideDown();
    }
  });
});
