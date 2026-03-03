(function ($) {
    function updatePreview(attachmentId, attachmentUrl) {
        const preview = $('.bml-logo-preview');
        if (!preview.length) {
            return;
        }

        if (attachmentId && attachmentUrl) {
            preview.html('<img src="' + attachmentUrl + '" style="max-width:200px;height:auto;" alt="Logo preview" />');
            $('.bml-remove-logo').prop('disabled', false);
        } else {
            preview.html('<em>No logo selected yet.</em>');
            $('.bml-remove-logo').prop('disabled', true);
        }
    }

    $(function () {
        const colorInput = $('#bml-brand-color');
        if (colorInput.length && colorInput.wpColorPicker) {
            colorInput.wpColorPicker();
        }

        let mediaFrame;
        $('.bml-upload-logo').on('click', function (e) {
            e.preventDefault();

            if (mediaFrame) {
                mediaFrame.open();
                return;
            }

            mediaFrame = wp.media({
                title: BML_SETTINGS.chooseText,
                button: { text: BML_SETTINGS.chooseText },
                library: { type: 'image' },
                multiple: false
            });

            mediaFrame.on('select', function () {
                const attachment = mediaFrame.state().get('selection').first().toJSON();
                $('#bml-logo-id').val(attachment.id);
                updatePreview(attachment.id, attachment.url);
            });

            mediaFrame.open();
        });

        $('.bml-remove-logo').on('click', function (e) {
            e.preventDefault();
            $('#bml-logo-id').val('');
            updatePreview('', '');
        });
    });
})(jQuery);
