jQuery(function(s) {

    function loading() {
        document.querySelectorAll(".bar").forEach(function(current) {
            let startWidth = 0;
            const endWidth = current.dataset.size;
            score = current.getAttribute('data-size');
            switch (true) {
                case (score < 70 ):
                    current.classList.add('bad');
                    break;
                case  (score >= 70 && score < 85):
                    current.classList.add('avg');
                    break;
                case   (score >= 85):
                    current.classList.add('excellent');
                default:
               }

             const interval = setInterval(frame, 20);

            function frame() {
                if (startWidth >= endWidth) {
                    clearInterval(interval);
                } else {
                    startWidth++;
                    current.style.width = `${endWidth}%`;
                    current.firstElementChild.innerText = `${startWidth}%`;
                }
            }
        });
    }
    setTimeout(loading, 1000);
});