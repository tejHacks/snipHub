document.addEventListener("DOMContentLoaded", function () {
    function createBubble() {
        const bubble = document.createElement("div");
        bubble.classList.add("bubble");
        document.body.appendChild(bubble);

        const size = Math.random() * 30 + 10;
        bubble.style.width = `${size}px`;
        bubble.style.height = `${size}px`;
        bubble.style.left = `${Math.random() * 100}%`;
        bubble.style.animationDuration = `${Math.random() * 4 + 2}s`;

        setTimeout(() => bubble.remove(), 5000);
    }

    setInterval(createBubble, 500);
});
