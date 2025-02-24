//Dismiss
jQuery(document).on('click', '.oum-getting-started-notice .notice-dismiss', function() {
    jQuery.ajax({
        url: ajaxurl,
        data: {
            action: 'cbn_dismiss_getting_started_notice'
        }
    });
});


response.data.locations = undefined;
response.data.datetime = undefined;
jQuery(function($){
  // Audio Uploader
  $('body').on('click', '.cbn_upload_audio_button', function(e){
    e.preventDefault();

    let audio_uploader = wp.media({
        title: 'Custom audio',
        library : {
            type : 'audio'
        },
        button: {
            text: 'Use this audio'
        },
        multiple: false
    }).on('select', function() {
        let attachment = audio_uploader.state().get('selection').first().toJSON();
        let url = attachment.url;
        $('#cbn_location_audio').val(url);
        $('#cbn_location_audio_preview').addClass('has-audio');
        $('#cbn_location_audio_preview').html(url + '<div onclick="oumRemoveAudioUpload()" class="remove-upload">&times;</div>');
    });

    audio_uploader.open();
  });

  // Icon Uploader
  $('body').on('click', '.cbn_upload_icon_button', function(e){
    e.preventDefault();

    let icon_uploader = wp.media({
        title: 'Custom icon',
        library : {
            type : 'image'
        },
        button: {
            text: 'Use this image'
        },
        multiple: false
    }).on('select', function() {
        let attachment = icon_uploader.state().get('selection').first().toJSON();
        let url = attachment.url;
        $('#cbn_marker_user_icon').val(url);
        $('#cbn_marker_user_icon_preview').addClass('has-icon');
        $('#cbn_marker_user_icon_preview').css("background-image", "url(" + url + ")");
        $('#cbn_marker_user_icon_preview').next('input[type=radio]').prop('checked', true);
        $('#cbn_marker_user_icon_preview').next('input[type=radio]').trigger('change');
    });
    
    icon_uploader.open();
  });

  // Export CSV
  $('body').on('click', '.cbn_export_csv_button', function(e){
    e.preventDefault();

      let ajaxurl;
      jQuery.ajax({
        url: ajaxurl,
        type: 'POST',
        dataType: 'json',
        data: {
            'action': 'cbn_csv_export',
        },
        success: function (response, textStatus) {
            console.log(response);
            console.log(textStatus);

            // locations from PHP
            let $locations_list = response.data.locations;
            let datetime = response.data.datetime;

            // EXIT, if no locations
            if($locations_list.length === 0) {
                alert('Something went wrong. Please see errors in console.');
                console.error('OUM: No public locations available to export.');
                return;
            } 

            let download = function (data) {
              let blob = new Blob([data], { type: 'text/csv' });
              let url = window.URL.createObjectURL(blob)
              let a = document.createElement('a')
              a.setAttribute('href', url)
              a.setAttribute('download', 'oum-locations_' + datetime + '.csv');
              a.click()
            }

            let csvmaker = function (data) {
                let csvRows = [];
              let headerValues = '';
              for (let col of data.header) { headerValues += '"' + col + '"' + ','; }
              csvRows.push(headerValues.slice(0, -1));
              data.rows.forEach(row => {
                let locationValues = '';
                for (let col of row) { locationValues += '"' + col + '"' + ','; }
                csvRows.push(locationValues.slice(0, -1));
              });
              return csvRows.join('\r\n')
            }

            let get = function () {
              let data = {};
              data.header = Object.keys($locations_list[0]);
              data.rows = [];
              $locations_list.forEach(location_row => {
                data.rows.push(Object.values(location_row))
              });
              console.log(data);
              let csvdata = csvmaker(data);
              download(csvdata);
            }
            
            get();
        }
    });
  });

  // Import CSV
  $('body').on('click', '.cbn_upload_csv_button', function(e){
    e.preventDefault();

      let wp;
      let button = $(this),
          csv_uploader = wp.media({
              title: 'Upload CSV file',
              library: {
                  type: 'file'
              },
              button: {
                  text: 'Use this file'
              },
              multiple: false
          }).on('select', function () {
              let attachment = csv_uploader.state().get('selection').first().toJSON();

              // Show loading spinner
              if (!$('.oum-import-loading').length) {
                  button.after('<div class="oum-import-loading"><div class="oum-spinner"></div></div>');
              }
              $('.oum-import-loading').show();
              button.prop('disabled', true);

              // Import CSV with PHP
              let cbn_ajax;
              let ajaxurl;
              let jQuery;
              jQuery.ajax({
                  url: ajaxurl,
                  type: 'POST',
                  dataType: 'json',
                  data: {
                      'action': 'cbn_csv_import',
                      'cbn_location_nonce': cbn_ajax.cbn_location_nonce,
                      'url': attachment.url,
                  },
                  success: function (response) {
                      // Hide loading spinner
                      $('.oum-import-loading').hide();
                      button.prop('disabled', false);

                      if (response.success) {
                          alert(response.data);
                      } else {
                          alert('Something went wrong. Please see errors in console.');
                          response.data.forEach((error) => {
                              console.error(error.code + ': ' + error.message);
                          });
                      }
                  },
                  error: function () {
                      // Hide loading spinner
                      $('.oum-import-loading').hide();
                      button.prop('disabled', false);
                      alert('Something went wrong. Please try again.');
                  }
              });
          })
              .open();
  });
});
function oumRemoveAudioUpload() {
    document.getElementById('cbn_location_audio').value = '';
    document.getElementById('cbn_location_audio_preview').classList.remove('has-audio');
    document.getElementById('cbn_location_audio_preview').textContent = '';
}