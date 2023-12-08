$(document).ready(function () {
    var container = $('.container').eq(0);
    var playInfo = container.find('.play-info');
    var playersInfo = container.find('.players-info');
    var playersInfoContent = playersInfo.html();
    var warning = container.find('.warning'); 
    var newGame = container.find('#new-game')
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
                    playersInfo.fadeOut(600);
                    setTimeout(function () {
                        playInfo.css("visibility", "visible").show(600);
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
                    }, 600)
                }
                else {
                    playInfo.html(jsonResponse.error);
                    playInfo.css("visibility", "visible").show(600);
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
                                            playInfo.html("Play finsh 🙌");
                                            warning.html(jsonResponse.moveDetils.result);
                                            warning.show();
                                            setTimeout(function(){
                                                newGame.show();
                                            },700);
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

        newGame.on('click',function(){
            $(this).fadeOut(600);
            playInfo.html("");
            warning.html("");
            playInfo.css("visibility", "hidden");
            warning.fadeOut(600);
            playersInfo.fadeOut(600);
            playersInfo.html(playersInfoContent);
            playersInfo.fadeIn(600);
        })
    })
});