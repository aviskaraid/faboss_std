(function (window) {

    window.convertDateToText = function (mysqlDate) {
        if (!mysqlDate || typeof mysqlDate !== 'string') {
            return '';
        }

        const datePart = mysqlDate.trim().split(' ')[0];
        const parts = datePart.split('-');

        if (parts.length !== 3) {
            return '';
        }

        return parts[2] + '-' + parts[1] + '-' + parts[0];
    };

})(window);

(function (window) {

    window.convertTextToDate = function (textDate) {
        if (!textDate || typeof textDate !== 'string') {
            return '';
        }

        const parts = textDate.trim().split('-');

        if (parts.length !== 3) {
            return '';
        }

        const day   = parts[0];
        const month = parts[1];
        const year  = parts[2];

        // Basic numeric validation
        if (
            day.length !== 2 ||
            month.length !== 2 ||
            year.length !== 4 ||
            isNaN(day) ||
            isNaN(month) ||
            isNaN(year)
        ) {
            return '';
        }

        return year + '-' + month + '-' + day;
    };

})(window);

(function ($) {
    console.log('fungsi-helper.js loaded');

    let isActualSize = false;
    let currentType = ""; // image | pdf

    $(document).on('click', '.btn-bukti', function(e){
        e.preventDefault();

        let fileUrl = $(this).data('file');
        if (!fileUrl) return;

        let ext = fileUrl.split('.').pop().toLowerCase();
        resetPreview();

        if (['jpg','jpeg','png'].includes(ext)) {
            currentType = 'image';
            $('#imgPreview').attr('src', fileUrl).show();
        } 
        else if (ext === 'pdf') {
            currentType = 'pdf';
            $('#pdfPreview').attr('src', fileUrl).show();
        }

        $('#modalBukti').modal('show');
    });

    $(document).on('click', '#toggleSize', function(){
        if (currentType === 'image') {
            if (!isActualSize) {
                $('#imgPreview').css({ 'max-width': 'none', 'width': 'auto' });
                $(this).html('<i class="fas fa-compress"></i> Fit to Screen');
            } else {
                $('#imgPreview').css({ 'max-width': '100%', 'width': '100%' });
                $(this).html('<i class="fas fa-search-plus"></i> Actual Size');
            }
        }

        if (currentType === 'pdf') {
            if (!isActualSize) {
                $('#pdfPreview').css({ 'width': '1200px', 'height': '1600px' });
                $(this).html('<i class="fas fa-compress"></i> Fit to Screen');
            } else {
                $('#pdfPreview').css({ 'width': '100%', 'height': '100%' });
                $(this).html('<i class="fas fa-search-plus"></i> Actual Size');
            }
        }

        isActualSize = !isActualSize;
    });

    function resetPreview() {
        isActualSize = false;
        currentType = "";

        $('#imgPreview').hide().attr('src','').css({
            'max-width':'100%',
            'width':'100%'
        });

        $('#pdfPreview').hide().attr('src','').css({
            'width':'100%',
            'height':'100%'
        });

        $('#toggleSize').html('<i class="fas fa-search-plus"></i> Actual Size');
    }

    $(document).on('hidden.bs.modal', '#modalBukti', function () {
        resetPreview();
    });

})(jQuery);

