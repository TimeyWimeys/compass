/* jshint esversion: 11 */
/* jshint browser: true, devel: true */
/* global jQuery, ajaxurl, wp, console */
document.addEventListener('DOMContentLoaded', function () {
    // Event: "Add Location"-Form send
    "use strict";
    jQuery('#cbn_add_location').submit(function (event) {
        jQuery('#cbn_submit_btn').addClass('cbn-loading');

        event.preventDefault();
        let formData = new FormData(this);

        // Get all images (both existing and new) in their current order
        let previewContainer = document.getElementById('cbn_location_images_preview');
        let previewItems = previewContainer.querySelectorAll('.image-preview-item');
        let imageOrder = [];

        // Add existing and new images to formData in their current order
        previewItems.forEach((item, index) => {
            if (item.classList.contains('existing-image')) {
                // For existing images, get the URL from the hidden input
                let imgUrl = item.querySelector('[name="existing_images[]"]').value;
                formData.append('existing_images[]', imgUrl);
                imageOrder.push('existing:' + imgUrl);
            } else {
                // For new images, get the file from selectedFiles using the filename
                let fileName = item.dataset.fileName;
                let file = window.cbnSelectedFiles.find(f => f.name === fileName);
                if (file) {
                    formData.append('cbn_location_images[]', file);
                    imageOrder.push('new:' + fileName);
                }
            }
        });

        // Add the complete image order
        formData.append('image_order', JSON.stringify(imageOrder));

        formData.append('action', 'cbn_add_location_from_frontend');

        jQuery.ajax({
            type: 'POST',
            url: cbn_ajax.ajaxurl,
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function (response, textStatus, XMLHttpRequest) {
                jQuery('#cbn_submit_btn').removeClass('cbn-loading');

                if (response.success === false) {
                    cbnShowError(response.data);
                }
                if (response.success === true) {
                    jQuery('#cbn_add_location').trigger('reset');

                    // Determine message type based on action
                    if (document.getElementById('cbn_delete_location').value === 'true') {
                        // For deletion
                        CBNFormController.showFormMessage(
                            'success',
                            wp.i18n.__('Location deleted', 'compass'),
                            wp.i18n.__('The location has been successfully removed from the map.', 'compass'),
                            wp.i18n.__('Close and refresh map', 'compass'),
                            function () {
                                window.location.reload();
                            }
                        );
                    } else if (document.getElementById('cbn_post_id').value) {
                        // For edits
                        CBNFormController.showFormMessage(
                            'success',
                            wp.i18n.__('Changes saved', 'compass'),
                            wp.i18n.__('Your changes have been saved and will be visible after we reviewed them.', 'compass'),
                            wp.i18n.__('Close and refresh map', 'compass'),
                            function () {
                                window.location.reload();
                            }
                        );
                    } else {
                        // For new locations
                        if (typeof cbn_action_after_submit !== 'undefined') {
                            if (cbn_action_after_submit === 'refresh') {
                                window.location.reload();
                            } else if (cbn_action_after_submit === 'redirect' && typeof thankyou_redirect !== 'undefined' && thankyou_redirect !== '') {
                                window.location.href = thankyou_redirect;
                            } else {
                                // Show thank you message with refresh button (default)
                                let thankyouDiv = document.getElementById('cbn_add_location_thankyou');
                                let thankyouHeadline = thankyouDiv?.querySelector('h3')?.textContent || wp.i18n.__('Thank you!', 'compass');
                                let thankyouText = thankyouDiv?.querySelector('.cbn-add-location-thankyou-text')?.textContent || wp.i18n.__('We will check your location suggestion and release it as soon as possible.', 'compass');

                                CBNFormController.showFormMessage(
                                    'success',
                                    thankyouHeadline,
                                    thankyouText,
                                    wp.i18n.__('Close and refresh map', 'compass'),
                                    function () {
                                        window.location.reload();
                                    }
                                );
                            }
                        } else {
                            // Fallback to thank you message with refresh button
                            let thankyouDiv = document.getElementById('cbn_add_location_thankyou');
                            let thankyouHeadline = thankyouDiv?.querySelector('h3')?.textContent || wp.i18n.__('Thank you!', 'compass');
                            let thankyouText = thankyouDiv?.querySelector('.cbn-add-location-thankyou-text')?.textContent || wp.i18n.__('We will check your location suggestion and release it as soon as possible.', 'compass');

                            CBNFormController.showFormMessage(
                                'success',
                                thankyouHeadline,
                                thankyouText,
                                wp.i18n.__('Close and refresh map', 'compass'),
                                function () {
                                    window.location.reload();
                                }
                            );
                        }
                    }
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    });

    function cbnShowError(errors) {
        let errorWrapEl = jQuery('#cbn_add_location_error');
        errorWrapEl.html('');
        errors.forEach(error => {
            errorWrapEl.append(error.message + '<br>');
        });
        errorWrapEl.show();
    }
});