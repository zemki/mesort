export default {
    methods: {
        createCircles(circles, mainContainer = document.querySelector(".circle_container")) {


            let maxDiameter = 60 + this.circles * 2;
            let isAndroid = /Android/i.test(navigator.userAgent || navigator.vendor || window.opera);

            for (let i = circles; i >= 1; i--) {
                let circle = document.createElement("div");
                circle.className = "round-sorting" + i;

                let diameter = (i / this.circles) * maxDiameter;
                let size = diameter + "vh";

                circle.style.width = size;
                circle.style.height = size;
                circle.style.borderRadius = "50%";
                circle.style.position = "absolute";
                circle.style.bottom = "0";
                circle.style.right = "0";
                circle.style.margin = "0";
                circle.style.border = "1px solid black";
                mainContainer.appendChild(circle);
            }

            if (isAndroid) {
                document.querySelector(".round-sorting" + circles).style.width = "50vh";
                document.querySelector(".round-sorting" + circles).style.height = "50vh";
                document.querySelector(".round-sorting" + circles).style.borderRadius = "50%";

                document.querySelector(".round-sorting" + circles).style.marginTop = "-5%";

                for (let i = 1; i <= circles - 1; i++) {
                    document.querySelector(".round-sorting" + i.toString()).style.width = ((i / circles) * 50).toString() + "vh";
                    document.querySelector(".round-sorting" + i.toString()).style.height = ((i / circles) * 50).toString() + "vh";
                }
            } else {
                document.querySelector(".round-sorting" + circles).style.width = (60 + circles * 2).toString() + "vh";
                document.querySelector(".round-sorting" + circles).style.height = (60 + circles * 2).toString() + "vh";
                document.querySelector(".round-sorting" + circles).style.borderRadius = "50%";

                for (let i = 1; i <= circles - 1; i++) {
                    document.querySelector(".round-sorting" + i.toString()).style.width = ((i / circles) * (60 + circles * 2)).toString() + "vh";
                    document.querySelector(".round-sorting" + i.toString()).style.height = ((i / circles) * (60 + circles * 2)).toString() + "vh";
                }
            }
        },
        createSections(howMany, circles, center_x = this.interview.center_x, center_y = this.interview.center_y, label = this.centerLabel) {
            const centerLabel = document.getElementById("centerLabel");
            if (centerLabel) centerLabel.remove();
            document.querySelectorAll(".sectionDivider").forEach((e) => e.remove());
            this.appendDivsAndCalculateValues(howMany, circles, center_x, center_y, label);
        },
        appendDivsAndCalculateValues(howMany, circles, center_x = this.interview.center_x, center_y = this.interview.center_y, label) {
            let container = document.getElementById("sortingsdiv");
            let diameter = document.querySelector(".round-sorting" + circles).offsetWidth;
            let howManyNumber = howMany.length;

            const mainNav = document.getElementById("main-nav-interview");
            const navHeight = mainNav ? mainNav.offsetHeight : 0;
            center_y += navHeight;
            let radius = diameter / 2;

            // Calculate the angle between sections
            let angleIncrement = 360 / howManyNumber;

            for (let i = 0; i < howManyNumber; i++) {
                // Create the divider element
                let divider = document.createElement("div");
                divider.className = "border-solid border-black border-2 absolute bg-transparent sectionDivider";
                divider.style.width = "2px"; // Width of the line
                divider.style.height = radius + "px"; // Radius of the circle

                // Position the dividers
                divider.style.left = center_x + "px";
                divider.style.top = center_y - radius + "px"; // Set to top of circle

                // Rotate the divider
                divider.style.transform = `rotate(${angleIncrement * i}deg)`;
                divider.style.transformOrigin = "bottom";

                // Append the divider to the container
                container.appendChild(divider);
            }

            this.setCenterLabel(container, center_x, center_y, label);
        },
        setCenterLabel(container, center_x, center_y, label = this.centerLabel) {


            const mainNav = document.getElementById("main-nav-interview");
            const navbarHeight = mainNav ? mainNav.offsetHeight : 0;

            let centerLabel = document.createElement("div");
            centerLabel.className = "bg-gray-900 text-white absolute rounded-full text-center align-middle p-3";
            centerLabel.id = "centerLabel";
            centerLabel.style.height = "50px";
            centerLabel.style.width = "50px";
            centerLabel.style.left = center_x - 25 + "px";
            centerLabel.style.top = center_y + navbarHeight - 25 + "px";
            centerLabel.innerText = label;

            container.appendChild(centerLabel);
        }
    }
};
