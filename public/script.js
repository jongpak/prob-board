/*
 * src: http://stackoverflow.com/questions/7719955/replace-text-but-not-the-html-tags
 */
function searchHTML(searchString, htmlString) {
    var expr = new RegExp(searchString, 'gi');
    var container = $('<div>').html(htmlString);
    var elements = container.find('*').addBack();
    var textNodes = elements.contents().not(elements);

    textNodes.each(function() {
        var matches = this.nodeValue.match(expr);

        if (matches) {
            var parts = this.nodeValue.split(expr);

            for (var n = 0; n < parts.length; n++) {
                if (n) {
                    $('<span class="search-keyword">').text(matches[n - 1]).insertBefore(this);
                }
                if (parts[n]) {
                    $(document.createTextNode(parts[n])).insertBefore(this);
                }
            }

            $(this).remove();
        }
    });

    return container.html();
}