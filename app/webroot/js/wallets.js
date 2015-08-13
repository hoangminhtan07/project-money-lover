$(document).ready(function () {
    //redirect url between viewDay and viewCategory
    $('#choose').change(function () {
        var currentUrl = window.location.href;
        var val = $(this).val(); //or this.value
        val = 'cate-' + val;
        var splitUrl = currentUrl.split('/');
        var key = splitUrl[5];
        var newUrl = currentUrl.replace(key, val);
        window.location.href = newUrl;
    });

    //redirect button addTransaction
    $('.btn-success').on('click', function () {
        window.location.href = 'transactions/add';
    });

    //choose date input use datepicker
    $(function () {
        $('.date_input').datepicker({
            inline: true,
            showOtherMonths: true
        })
                .datepicker('widget').wrap('<div class="ll-skin-nigran"/>');
    });

    //get day to use in TransactionController
    $('.btn-info').on('click', function () {
        var fday = $('#from_date').val(),
                tday = $('#to_date').val();
        fday = formatDate(fday);
        tday = formatDate(tday);
        var currentUrl = window.location.href;
        var splitUrl = currentUrl.split('/');

        var newUrl = splitUrl[0] + '/' + splitUrl[1] + '/' + splitUrl[2] + '/' + splitUrl[3] + '/' + splitUrl[4] + '/' + splitUrl[5] + '/' + fday + '/' + tday;

        window.location.href = newUrl;

    });

    /**
     * Format date to 'y-m-d'
     * 
     * @param string date
     * @returns date 
     */
    function formatDate(date) {
        var valDay = new Date(date);
        month = '' + (valDay.getMonth() + 1),
                day = '' + valDay.getDate(),
                year = valDay.getFullYear();

        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;

        return [year, month, day].join('-');
    }

});
