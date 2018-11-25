function initIndexPage() {
    
    var userName = $("#user-name-field").val();

    // Prevent special characters in input
    $('input').on('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
        event.preventDefault();
        return false;
        }
    });
    
    // Enable using enter when adding words
    $("#termId").keyup(function(event) {
        if (event.keyCode === 13) {
            $("#add-btn").click();
        }
    });

    // Log out click
    $("#logout-btn").click(function(){
        $.ajax({
            url: 'logout.php',
            type: 'POST',
            data: {'userName': userName},
            success: function(data) {
                location.reload();
            }
        });
    });

    // Add a new search word click
    $("#add-btn").click(function() {
        var word = $("#termId").val();
        if (word != '') {
            $.ajax({
                url: 'api.php',
                type: 'POST',
                data: {'userName': userName, 'searchWord': word, 'action': 'addWord' },
                success: function(data) {
                    var newWord = `<div id='entry-${word}' class='word-btn'>
                                        ${word}
                                        <div class='delete-btn' value='${word}'>
                                            <i class='fas fa-times-circle'></i>
                                        </div>
                                    </div>`;
                    $("#searchTerms").append(newWord);
                    $("#termId").val('');
                    getNewsArticles(userName);
                }
            });
        }
    });

    // Register click event for delete buttons
    $(document).on('click', '.delete-btn', function(){
        var id = $(this).attr("value");
        $.ajax({
            url: 'api.php',
            type: 'POST',
            data: {'userName': userName, 'wordId':id, 'action': 'deleteWord' },
            success: function(data) {
                $("#entry-"+id).hide(200, function(){ 
                    $(this).remove();
                    getNewsArticles(userName); 
                });
            },
            error: function(data) {
                statusMessage('error', data.responseJSON.message);
            }
        });
    });

    getNewsArticles(userName);
}

// Check email formatting
function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

function statusMessage(type, message) {
    var el = $("#status-msg");
    if (type == 'error') {
        el.addClass("msg-error");
    } else if (type == 'success') {
        el.addClass("msg-success");
    }
    el.html(message);
}

// Sort on element
function sortArray(dataArray, sortWord) {
    dataArray.sort(function(a, b) {
        if (a[sortWord] > b[sortWord]) return -1;
        if (b[sortWord] > a[sortWord]) return 1;
        return 0;
    });
    return dataArray;
}

// Render articles to the DOM
function renderNewsArticles(articles) {
    if (articles.length > 0) {
        $("#articles-list").html("").hide();
        var articlesParsed = articles;
        $.each(articlesParsed, function(key,value){
            var template = 
            `<div class='article'>
                <a target='_blank'  href="${ value.link }">
                    <h3>${ value.date }</h3>
                    <h2 class='article-title'>${ value.title }</h2>
                    <div class='article-desc'>${ value.desc }</div>
                    <div class='article-foot'>Source: ${ value.url }<br>${ value.word }</div>
                </a>
            </div>`;
            $("#articles-list").append(template);
        });
        $("#articles-list").fadeIn();
    } else {
        $("#articles-list").fadeOut();
    }
}

// Fetch articles
function getNewsArticles(userName) {
    // check if there are any words to prevent unnecessary searches
    var btnCount = $(".word-btn").size();
    if (btnCount > 0) {
    $(".load-icon").fadeIn();
    $.ajax({
            url: 'api.php',
            type: 'GET',
            data: {'userName': userName, 'action': 'getArticles'},
            success: function(data) {
                data = $.parseJSON(data);
                data = sortArray(data, "date");
                renderNewsArticles(data);
                $(".load-icon").hide();
            },
            error: function(data) {
                statusMessage('error', data.responseJSON.message);
                $(".load-icon").fadeOut();
            }
        });
    } else {
        $(".load-icon").hide();
        $("#articles-list").fadeOut();
    }
}