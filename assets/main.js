$(function() {
    $('#submitForm').on('click', function(e) {
        var districtForm = $('#districtForm');
        var populationFrom = districtForm.find('input[name="populationFrom"]');
        var populationTo = districtForm.find('input[name="populationTo"]');
        var areaFrom = districtForm.find('input[name="areaFrom"]');
        var areaTo = districtForm.find('input[name="areaTo"]');
        var city = districtForm.find('select[name="city"]');

        $.ajax({
            url: '/districts/'+city.val(),
            method: 'GET',
            data: {
                population: populationFrom.val()+'|'+populationTo.val(),
                area: areaFrom.val()+'|'+areaTo.val()
            },
            success: function(data) {
                var parser = new DOMParser();
                var wrapper = parser.parseFromString(data, "text/html");
                var table = wrapper.getElementsByTagName('table');
                
                $('table').html(table);
            },
            error: function(data) {}
        });
        return false;
    });
    
    $('table').on('click','.asc, .desc' ,function(){
        var sortName = $(this).parent().attr('id');
        var order = $(this).attr('class');
        var districtForm = $('#districtForm');
        var populationFrom = districtForm.find('input[name="populationFrom"]');
        var populationTo = districtForm.find('input[name="populationTo"]');
        var areaFrom = districtForm.find('input[name="areaFrom"]');
        var areaTo = districtForm.find('input[name="areaTo"]');
        var city = districtForm.find('select[name="city"]');

        $.ajax({
            url: '/districts/'+city.val(),
            method: 'GET',
            data: {
                sort: sortName,
                order: order,
                population: populationFrom.val()+'|'+populationTo.val(),
                area: areaFrom.val()+'|'+areaTo.val()
            },
            success: function(data) {
                var parser = new DOMParser();
                var wrapper = parser.parseFromString(data, "text/html");
                var table = wrapper.getElementsByTagName('table');
                
                $('table').html(table);
            },
            error: function(data) {}
        });
        return false;
    });
});