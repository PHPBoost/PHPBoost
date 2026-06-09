(function() {
    // Add line numbers to precode blocks that are not inline
    function addLineNumbers(pre) {
        if (pre.classList.contains('precode-inline')) return;
        if (pre.querySelector('.line-number')) return;
        var span = document.createElement('span');
        span.className = 'line-number';
        pre.insertBefore(span, pre.firstChild);
        var lines = (pre.querySelector('code') || pre).textContent.split(/\n/).length;
        for (var j = 0; j < lines; j++) {
            var lineSpan = document.createElement('span');
            lineSpan.textContent = (j + 1);
            span.appendChild(lineSpan);
        }
    }

    // Run Prism highlighting on all code blocks, then add line numbers
    function initPrism() {
        if (typeof Prism === 'undefined') return;

        var codeBlocks = document.querySelectorAll('pre.precode code[class*="language-"]');
        for (var i = 0; i < codeBlocks.length; i++) {
            Prism.highlightElement(codeBlocks[i]);
        }

        var preBlocks = document.querySelectorAll('pre.precode:not(.precode-inline)');
        for (var k = 0; k < preBlocks.length; k++) {
            addLineNumbers(preBlocks[k]);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPrism);
    } else {
        initPrism();
    }
})();