<div class="document-preview-area position-relative w-100">
    <div class="pdf_viewer_box overflow-auto">
        <div id="canvas_container" class="w-100 h-100 text-center overflow-auto">
            <canvas id="pdf_renderer"></canvas>
        </div>

        <div class="pdf_navigation_controls position-absolute d-flex">
            <button id="pdf_previous_btn" class="h-100">Previous</button>
            <input id="pdf_current_page" value="1" type="number" class="w-100" disabled style="appearance: textfield;" />
            <button id="pdf_next_btn" class="h-100">Next</button>
        </div>

        <div class="pdf_zoom_controls position-absolute text-center">
            <button class="btn btn-two" id="pdf_zoom_out"><i class="fas fa-search-minus"></i></button>
            <button class="btn btn-two" id="pdf_zoom_in"><i class="fas fa-search-plus"></i></button>
            <button class="btn btn-two"
                onclick="
                    let element = document.querySelector('.document-preview-area');
                    if (element) {
                        if (!document.fullscreenElement) {
                            element.requestFullscreen();
                        } else if (document.exitFullscreen) {
                            document.exitFullscreen();
                        }
                    }
                ">
                <i class="fa fa-expand"></i>
            </button>
        </div>
    </div>
</div>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('frontend/js/pdf.worker.min.js') }}";

    var myState = {
        pdf: null,
        currentPage: 1,
        zoom: 0.8,
        hasTriggeredCompletion: false // New flag to track completion
    };

    pdfjsLib
        .getDocument("{{ route('student.get-file-direct', ['id' => $file_path]) }}")
        .promise.then((pdf) => {
            myState.pdf = pdf;
            render();

            // Add scroll event listener to the canvas container
            document.getElementById('canvas_container').addEventListener('scroll', checkScroll);
        })
        .catch(function(error) {
            console.error("PDF loading error:", error);
        });

    function render() {
        // Reset completion trigger when page changes
        myState.hasTriggeredCompletion = false;

        myState.pdf.getPage(myState.currentPage).then((page) => {
            var canvas = document.getElementById("pdf_renderer");
            var ctx = canvas.getContext("2d");
            var viewport = page.getViewport({
                scale: myState.zoom
            });

            canvas.width = viewport.width;
            canvas.height = viewport.height;

            page.render({
                canvasContext: ctx,
                viewport: viewport,
            }).promise.then(() => {
            // Mark that we're initially at bottom (for last page)
            if (myState.currentPage === myState.pdf._pdfInfo.numPages) {
                const container = document.getElementById('canvas_container');
                container.wasAlreadyAtBottom = true;
            }
        });
        });
    }

    function checkScroll() {
        // Don't check if we've already triggered completion
        if (myState.hasTriggeredCompletion) return;

        const container = document.getElementById('canvas_container');
        const canvas = document.getElementById('pdf_renderer');

        // Check if scrolled to bottom (with 10px threshold)
        const isAtBottom = container.scrollTop + container.clientHeight >= canvas.height - 10;

        // If this is the last page AND we're at the bottom
        if (myState.currentPage === myState.pdf._pdfInfo.numPages && isAtBottom) {
            // Only trigger if we weren't already at the bottom (user-initiated scroll)
            if (!container.wasAlreadyAtBottom) {
                // Set flag to prevent multiple triggers
                myState.hasTriggeredCompletion = true;

                // Your existing completion code
                let itemId = "{{ $file_info['id'] }}";
                let elements = document.querySelectorAll(`input[data-lesson-id="${itemId}"]`);
                if (elements.length && !elements[0].hasAttribute('checked')) {
                    // elements[0].click();
                    alert(1)
                    completeLesson(itemId)
                }
            }
        }

        // Update our tracking of whether we were already at bottom
        container.wasAlreadyAtBottom = isAtBottom;
    }

    // Navigation buttons (optimized version)
    document.getElementById("pdf_previous_btn").addEventListener("click", () => {
        if (myState.currentPage > 1) {
            myState.currentPage--;
            document.getElementById("pdf_current_page").value = myState.currentPage;
            render();
            scrollToCanvas();
        }
    });

    document.getElementById("pdf_next_btn").addEventListener("click", () => {
        if (myState.currentPage < myState.pdf._pdfInfo.numPages) {
            myState.currentPage++;
            document.getElementById("pdf_current_page").value = myState.currentPage;
            render();
            scrollToCanvas();
        }
    });

    // Helper function for scrolling
    function scrollToCanvas() {
        var canvas = document.getElementById("pdf_renderer");
        canvas.scrollIntoView({ behavior: 'smooth', block: 'start' });
        window.scrollTo(0, 0);
    }

    // Page number input
    document.getElementById("pdf_current_page").addEventListener("keypress", (e) => {
        if (!myState.pdf) return;

        if (e.key === 'Enter') {
            var desiredPage = document.getElementById("pdf_current_page").valueAsNumber;
            if (desiredPage >= 1 && desiredPage <= myState.pdf._pdfInfo.numPages) {
                myState.currentPage = desiredPage;
                document.getElementById("pdf_current_page").value = desiredPage;
                render();
            }
        }
    });

    // Zoom controls
    document.getElementById("pdf_zoom_in").addEventListener("click", () => {
        if (myState.pdf) {
            myState.zoom += 0.2;
            render();
        }
    });

    document.getElementById("pdf_zoom_out").addEventListener("click", () => {
        if (myState.pdf) {
            myState.zoom = Math.max(0.2, myState.zoom - 0.2); // Prevent zooming too small
            render();
        }
    });

    // Fullscreen button (improved)
    document.querySelector(".btn.btn-two[onclick]").addEventListener("click", function() {
        let element = document.querySelector('.document-preview-area');
        if (!document.fullscreenElement) {
            element.requestFullscreen().catch(err => {
                console.error("Fullscreen error:", err);
            });
        } else {
            document.exitFullscreen();
        }
    });
</script>
