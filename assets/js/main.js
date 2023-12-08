$(document).ready(function () {
    var container = $('.container').eq(0);
    var playInfo = container.find('.play-info');
    var startGame = container.find('.start-game');
    var playersInfo = container.find('.players-info');
    var warning = container.find('.warning'); 
    container.find('h3').on('click', function () {
        $(this).hide();
        playersInfo.fadeIn(1000);
    })

    container.find('form').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: 'hendel.php/start',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response);
                var jsonResponse = JSON.parse(response);
                console.log(jsonResponse.playStart);
                if(jsonResponse.success) {
                    playInfo.html(jsonResponse.playStart);
                    playInfo.show();
                    playersInfo.fadeOut(1000);
                    setTimeout(function () {
                        playersInfo.html(
                            ` <table border=1>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </table>`);
                        playersInfo.fadeIn(600);
                        set();
                    }, 1000)
                }
                else {
                    playInfo.html(jsonResponse.error);
                    playInfo.show();
                }
                function set() {
                    var cells = $('table').find('td');
                    cells.each(function (index) {
                        $(this).on('click', function () {
                            warning.html("");
                            warning.hide();
                            var x = $(this).parent().index();
                            var y = $(this).index();
                            $.ajax({
                                type: 'POST',
                                url: 'hendel.php/move',
                                data: { x: x, y: y },
                                success: function (response){
                                    var jsonResponse = JSON.parse(response);
                                    if(jsonResponse.success){
                                        playersInfo.html(`<table border=1>
                                        ${jsonResponse.moveDetils.board.map(function (tr) {
                                        return `<tr>
                                                    ${tr.map(function (td) {
                                                    return `<td>${td}</td>`;
                                                    }).join('')}
                                                </tr>`;
                                                }).join('')}
                                            </table>`);
                                        if(jsonResponse.moveDetils.result!=""){
                                            warning.html(jsonResponse.moveDetils.result);
                                            warning.show();
                                        } else  set();                                            
                                    } else{
                                        console.log("ho")
                                        warning.html(jsonResponse.error);
                                        warning.show();
                                    }
                                },
                                error: function (error){
                                    warning.html(jsonResponse.error);
                                    warning.show();
                                }
                            });
                        });
                    });
                }
            },
            error: function (error) {
                warning.html(error);
                warning.show();
            }
        });
    })
});