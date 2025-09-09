"use strict";
const csrf_token = $("meta[name='csrf-token']").attr("content");

const placeholder = `<div class="player-placeholder">
<div class="preloader-two player">
    <div class="loader-icon-two player"><img src="${preloader_path}" alt="Preloader"></div>
</div>
</div>`;

function extractGoogleDriveVideoId(url) {
    // Regular expression to match Google Drive video URLs
    var googleDriveRegex =
        /(?:https?:\/\/)?(?:www\.)?(?:drive\.google\.com\/(?:uc\?id=|file\/d\/|open\?id=)|youtu\.be\/)([\w-]{25,})[?=&#]*/;

    // Try to match the URL with the regular expression
    var match = url.match(googleDriveRegex);

    // If a match is found, return the video ID
    if (match && match[1]) {
        return match[1];
    } else {
        return null;
    }
}

function showSidebar() {
    $(".wsus__course_sidebar").addClass("show");
}

function hideSidebar() {
    $(".wsus__course_sidebar").removeClass("show");
}

$(document).ready(function () {
    $(document).on("contextmenu", function (e) {
        e.preventDefault();
        return false;
    });
    $(document).on("keydown", function (e) {
        if (
            e.which === 123 ||
            (e.ctrlKey &&
                e.shiftKey &&
                (e.which === "I".charCodeAt(0) ||
                    e.which === "J".charCodeAt(0))) ||
            (e.ctrlKey && e.which === "U".charCodeAt(0))
        ) {
            e.preventDefault();
            return false;
        }
    });

    //image popup init
    $(document).on("click", ".image-popup", function () {
        $.magnificPopup.open({
            items: {
                src: $(this).attr("src"),
            },
            type: "image",
        });
    });
    document.addEventListener("focusin", (e) => {
        if (
            e.target.closest(
                ".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root"
            ) !== null
        ) {
            e.stopImmediatePropagation();
        }
    });

    $(".form-check").on("click", function () {
        $(".form-check").removeClass("item-active");
        $(this).addClass("item-active");
    });

    $(".lesson-item").on("click", function () {
        // hide sidebar
        hideSidebar();
        let ids = $(".lesson-item")
            .map(function () {
                return $(this).attr("data-lesson-id");
            })
            .get();

        var lessonId = $(this).attr("data-lesson-id");
        var chapterId = $(this).attr("data-chapter-id");
        var courseId = $(this).attr("data-course-id");
        var type = $(this).attr("data-type");
        var duration = $(this).attr("data-duration");

        var index = ids.indexOf(lessonId);
        if (index !== 0) {
            var previousLessonId = ids[index - 1];
            if (
                !$("input[data-lesson-id=" + previousLessonId + "]").prop(
                    "checked"
                )
            ) {
                alert("You must complete the previous lesson first.");
                $("div[data-lesson-id=" + previousLessonId + "]").click();
                return false;
            }
        }

        $.ajax({
            method: "POST",
            url: base_url + "/student/learning/get-file-info",
            data: {
                _token: csrf_token,
                lessonId: lessonId,
                chapterId: chapterId,
                courseId: courseId,
                type: type,
            },
            beforeSend: function () {
                $(".video-payer").html(placeholder);
            },
            success: function (data) {
                // set lesson id on meta
                $("meta[name='lesson-id']").attr("content", data.file_info.id);
                let playerHtml;
                const { file_info } = data;

                if (
                    file_info.file_type != "video" &&
                    file_info.storage != "iframe" &&
                    (file_info.type == "lesson" ||
                        file_info.type == "aws" ||
                        file_info.type == "wasabi" ||
                        file_info.type == "live")
                ) {
                    if (file_info.storage == "upload") {
                        playerHtml = `<div class="resource-file">
                        <div class="file-info">
                            <div class="text-center">
                                <img src="/uploads/website-images/resource-file.png" alt="">
                                <h6>${resource_text}</h6>
                                <p>${file_type_text}: ${file_info.file_type}</p>
                                <p>${download_des_text}</p>
                                <form action="/student/learning/resource-download/${file_info.id}" method="get" class="download-form">
                                    <button type="submit" class="btn btn-primary">${download_btn_text}</button>
                                </form>
                            </div>
                        </div>
                    </div>`;
                    } else if (file_info.storage == "live") {
                        let btnHtml = "";
                        if (file_info.is_live_now == "started") {
                            btnHtml = `<h6>${le_hea}</h6>`;
                            btnHtml += `<p>${le_des} <b class="text-highlight">${file_info.end_time}</b></p>`;
                            if (
                                (file_info.live.type === "jitsi" &&
                                    file_info.course.instructor
                                        .jitsi_credential) ||
                                (file_info.live.type === "zoom" &&
                                    file_info.course.instructor.zoom_credential)
                            ) {
                                btnHtml += `<a href="${
                                    base_url +
                                    "/student/learning/" +
                                    file_info.course.slug +
                                    "/" +
                                    file_info.id
                                }" class="btn btn-two me-2">${open_w_txt}</a>`;
                            } else {
                                btnHtml += `<p>${
                                    file_info.live.type === "zoom"
                                        ? "Zoom"
                                        : "Jitsi"
                                } ${cre_mi_txt}</p>`;
                            }
                            if (
                                file_info.live.type === "zoom" &&
                                file_info.live.join_url
                            ) {
                                btnHtml += `<a target="_blank" href="${file_info.live.join_url}" class="btn">Zoom app</a>`;
                            }
                        } else if (file_info.is_live_now == "ended") {
                            btnHtml = `<h6>${le_fi_he}</h6>`;
                            btnHtml += `<p>${le_fi_des}</p>`;
                        } else {
                            btnHtml = `<h6>${le_wi_he}</h6>`;
                            btnHtml += `<p>${le_wi_des} <b class="text-highlight">${file_info.start_time}</b></p>`;
                        }

                        playerHtml = `<div class="resource-file">
                        <div class="file-info">
                            <div class="text-center">
                            <img src="${
                                base_url + "/frontend/img/online-learning.png"
                            }" alt="">
                                ${btnHtml}
                            </div>
                        </div>
                    </div>`;
                    } else {
                        playerHtml = `<div class="resource-file">
                        <div class="file-info">
                            <div class="text-center">
                                <img src="/uploads/website-images/resource-file.png" alt="">
                                <h6>${resource_text}</h6>
                                <p>${file_type_text}: ${file_info.file_type}</p>
                                <p>${open_des_txt}</p>
                                <a href="${file_info.file_path}" target="_blank" class="btn btn-primary">${open_txt}</a>
                            </div>
                        </div>
                    </div>`;
                    }
                } else if (
                    file_info.storage == "youtube" &&
                    (file_info.type == "lesson" || file_info.type == "live")
                ) {
                    playerHtml = `<video id="vid1" class="video-js vjs-default-skin" controls autoplay width="640" height="264"
                        data-setup='{ "plugins": { "watermark": { "file": "${watermark}", "xpos": "0", "ypos": "0" } }, "techOrder": ["youtube"], "sources": [{ "type": "video/youtube", "src": "${file_info.file_path}"}] }'>
                        </video>`;
                } else if (
                    file_info.storage == "vimeo" &&
                    (file_info.type == "lesson" || file_info.type == "live")
                ) {
                    playerHtml = `<video id="vid1" class="video-js vjs-default-skin vimeo" controls autoplay width="640" height="264"
                        data-setup='{ "plugins": { "watermark": { "file": "${watermark}", "xpos": "0", "ypos": "0" } },"techOrder": ["vimeo"], "sources": [{ "type": "video/vimeo", "src": "${file_info.file_path}"}] }'>
                        </video>`;
                } else if (
                    (file_info.storage == "upload" ||
                        file_info.storage == "external_link" ||
                        file_info.storage == "aws" ||
                        file_info.storage == "wasabi") &&
                    (file_info.type == "lesson" || file_info.type == "live")
                ) {
                    playerHtml = `<video src="${file_info.file_path}" type="video/mp4" id="vid1" class="video-js vjs-default-skin" controls autoplay width="640" height="264"
                        data-setup='{ "plugins": { "watermark": { "file": "${watermark}", "xpos": "0", "ypos": "0" } } }'>
                        </video>`;
                } else if (
                    file_info.storage == "google_drive" &&
                    file_info.type == "lesson"
                ) {
                    playerHtml = `<video id="vid1" class="video-js vjs-default-skin" controls autoplay width="640" height="264"
                        data-setup='{ "plugins": { "watermark": { "file": "${watermark}", "xpos": "0", "ypos": "0" } }, "techOrder": ["html5"], "sources": [{ "type": "video/mp4", "src": "/stream-video/${extractGoogleDriveVideoId(
                        file_info.file_path
                    )}"}] }' poster="/video-stream/${extractGoogleDriveVideoId(
                        file_info.file_path
                    )}"> </video>`;
                } else if (
                    file_info.type == "document" &&
                    file_info.file_type != "txt"
                ) {
                    playerHtml = data.view;
                } else if (
                    file_info.storage == "iframe" ||
                    file_info.type == "document"
                ) {
                    playerHtml = `<iframe class="iframe-video" src="${
                        file_info.type == "document"
                            ? base_url + file_info.file_path
                            : file_info.file_path
                    }" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe><div class="vjs-watermark"><img src="${watermark}"></div><div class="vjs-poster custom-poster"></div>`;
                } else if (file_info.type == "quiz") {
                    playerHtml = `<div class="resource-file">
                    <div class="file-info">
                        <div class="text-center">
                            <img src="/uploads/website-images/quiz.png" alt="">
                            <h6 class="mt-2">${file_info.title}</h6>
                            <p>${quiz_st_des_txt}</p>
                            <a href="/student/learning/quiz/${file_info.id}" class="btn btn-primary">${quiz_st_txt}</a>
                        </div>
                    </div>
                </div>`;
                } else {
                    playerHtml = `<div class="resource-file">
                        <div class="file-info">
                            <div class="text-center">
                                <img src="/uploads/website-images/announcement.svg" alt="rencana tindak lanjut">
                                <h6 class="mt-2">${file_info.title}</h6>
                                <p>Buka Halaman RTL untuk info lebih lanjut</p>
                                <a href="/student/learning/follow-up-action/${file_info.id}" class="btn btn-primary">Mulai Mengisi RTL</a>
                            </div>
                        </div>
                    </div>`;
                }

                // Resetting any existing player instance
                if (videojs.getPlayers()["vid1"]) {
                    videojs.getPlayers()["vid1"].dispose();
                }

                $(".video-payer").html(playerHtml);

                // Initializing the player For Youtube Video
                if (document.getElementById("vid1")) {
                    // videojs("vid1").ready(function () {
                    //     this.play();
                    // });
                    var player = videojs("vid1");

                    player.on("error", function () {
                        // Get the error using the error() method
                        var error = player.error();
                        if (error) {
                            showVideoError(player, error, courseId, lessonId);
                        }
                    });

                    // Inside your player.ready function where you handle the timeupdate event:
                    player.ready(function () {
                        // Add a flag to track if we've already marked this lesson as complete
                        let lessonCompleted = false;

                        player.one("loadedmetadata", function () {
                            var duration = player.duration();

                            if (duration && !isNaN(duration)) {
                                player.on("timeupdate", function () {
                                    var currentTime = player.currentTime();

                                    // Check if we're 1 second before the end and haven't completed yet
                                    if (
                                        currentTime >= duration - 1 &&
                                        !lessonCompleted
                                    ) {
                                        let itemId = lessonId;
                                        let elements =
                                            document.querySelectorAll(
                                                `input[data-lesson-id="${itemId}"]`
                                            );

                                        if (
                                            elements.length &&
                                            !elements[0].checked
                                        ) {
                                            // elements[0].click();
                                            completeLesson(lessonId);
                                            lessonCompleted = true; // Mark as completed
                                        }

                                        // Keep the listener active but skip future checks
                                    }
                                });
                            }
                        });

                        this.play();
                    });
                }

                // set lecture description
                $(".about-lecture").html(file_info.description || no_des_txt);

                // load qna's
                fetchQuestions(courseId, lessonId, 1, true);
            },
            error: function (xhr, status, error) {},
        });
    });

    function showVideoError(player, error, courseId, lessonId) {
        console.error("Video error:", error);
        // Create error HTML with black screen
        var playerHtml = `
        <div class="video-error-container">
            <img src="/frontend/img/black.png" alt="Black screen"
                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjQwIiBoZWlnaHQ9IjI2NCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjMDAwIi8+PC9zdmc+'; this.alt='Black background'">
            <div class="video-error-message">
                <p>📺 Playback Error</p>
                <p class="error-details">${
                    error.message || "Unable to play video"
                }</p>
                <p><small>Error code: ${error.code || "Unknown"}</small></p>
                <button class="btn" onclick="reportVideo()">Laporkan</button>
            </div>
            <form action="" style="display: none;" id="report-form">
            <input type="hidden" name="_token" value="${csrf_token}">
            <input type="hidden" name="module_id" value="${courseId}">
            <input type="hidden" name="module" value="Course">
            <input type="hidden" name="error_code" value="${error.code}">
            <input type="hidden" name="title" value="Video Youtube ${lessonId} Error">
            <input type="hidden" name="description" value="${error.message}">
            </form>
        </div>
    `;

        // Clear the container completely first
        if (videojs.getPlayers()["vid1"]) {
            videojs.getPlayers()["vid1"].dispose();
        }

        $(".video-payer").html(playerHtml);
    }

    window.reportVideo = function () {
        const Y17JxWAOWqgYx999V6v4hpTZyFaxbF =
            window.Y17JxWAOWqgYx999V6v4hpTZyFaxbF;

        swal.fire({
            title: "Laporkan Masalah Video",
            text: "Anda akan melaporkan masalah video ini. Apakah Anda yakin?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "##5751e1",
            cancelButtonColor: "#d33",
            confirmButtonText: "Laporkan!",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        Authorization: `Bearer ${Y17JxWAOWqgYx999V6v4hpTZyFaxbF}`,
                    },
                    method: "POST",
                    url: "/api/report-error",
                    data: new FormData(document.getElementById("report-form")),
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data.success) {
                            toastr.success(data.message);
                            setTimeout(function () {
                                window.location.href =
                                    "/student/enrolled-courses";
                            }, 2000);
                        } else {
                            toastr.error(data.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            toastr.error(value);
                        });
                    },
                });
            }
        });
    };

    // Course video button for small devices
    $(".wsus__course_header_btn").on("click", function () {
        $(".wsus__course_sidebar").addClass("show");
    });

    $(".wsus__course_sidebar_btn").on("click", function () {
        $(".wsus__course_sidebar").removeClass("show");
    });
});
