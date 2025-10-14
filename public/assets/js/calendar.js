import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import interactionPlugin from "@fullcalendar/interaction";

document.addEventListener("DOMContentLoaded", function () {
    const calendarEl = document.getElementById("calendar");
    if (!calendarEl) return;

    const eventModal = document.getElementById("eventModal");
    const titleInput = document.getElementById("event-title");
    const startInput = document.getElementById("event-start-date");
    const endInput = document.getElementById("event-end-date");
    const addBtn = document.querySelector(".btn-add-event");
    const updateBtn = document.querySelector(".btn-update-event");

    // === Helper ===
    const openModal = () => (eventModal.style.display = "flex");
    const closeModal = () => {
        eventModal.style.display = "none";
        resetModal();
    };
    const resetModal = () => {
        titleInput.value = "";
        startInput.value = "";
        endInput.value = "";
        document.querySelectorAll('input[name="event-level"]').forEach((r) => (r.checked = false));
    };

    // === Fetch events from backend ===
    async function fetchEvents() {
        const res = await fetch("/admin/calendar/events");
        return await res.json();
    }

    // === Store event to backend ===
    async function storeEvent(eventData) {
        const token = document.querySelector('meta[name="csrf-token"]').content;

        await fetch("/admin/calendar/store", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token,
            },
            body: JSON.stringify(eventData),
        });
    }

    // === Update event to backend ===
    async function updateEvent(id, eventData) {
        const token = document.querySelector('meta[name="csrf-token"]').content;

        await fetch(`/admin/calendar/update/${id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token,
            },
            body: JSON.stringify(eventData),
        });
    }

    // === Delete event from backend ===
    async function deleteEvent(id) {
        const token = document.querySelector('meta[name="csrf-token"]').content;

        await fetch(`/admin/calendar/delete/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": token,
            },
        });
    }

    // === Calendar Init ===
    fetchEvents().then((events) => {
        const calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
            initialView: "dayGridMonth",
            selectable: true,
            editable: true,
            events: events,

            // === Select date to add event ===
            select: function (info) {
                resetModal();
                startInput.value = info.startStr;
                endInput.value = info.endStr || info.startStr;
                addBtn.style.display = "block";
                updateBtn.style.display = "none";
                openModal();
            },

            // === Click existing event ===
            eventClick: function (info) {
                const event = info.event;
                titleInput.value = event.title;
                startInput.value = event.startStr.slice(0, 10);
                endInput.value = event.endStr ? event.endStr.slice(0, 10) : "";

                updateBtn.dataset.id = event.id;
                addBtn.style.display = "none";
                updateBtn.style.display = "block";
                openModal();
            },

            // === Drag & Drop event ===
            eventDrop: function (info) {
                const event = info.event;
                updateEvent(event.id, {
                    title: event.title,
                    start: event.startStr,
                    end: event.endStr,
                });
            },
        });

        calendar.render();

        // === Add event ===
        addBtn.addEventListener("click", async () => {
            const title = titleInput.value;
            const start = startInput.value;
            const end = endInput.value;

            if (!title || !start) {
                alert("Judul dan tanggal wajib diisi!");
                return;
            }

            const eventData = { title, start, end };
            calendar.addEvent(eventData);
            await storeEvent(eventData);
            closeModal();
        });

        // === Update event ===
        updateBtn.addEventListener("click", async () => {
            const id = updateBtn.dataset.id;
            const title = titleInput.value;
            const start = startInput.value;
            const end = endInput.value;

            const event = calendar.getEventById(id);
            if (event) {
                event.setProp("title", title);
                event.setDates(start, end);
                await updateEvent(id, { title, start, end });
            }

            closeModal();
        });

        // === Close modal ===
        document.querySelectorAll(".modal-close-btn").forEach((btn) => {
            btn.addEventListener("click", closeModal);
        });
        window.addEventListener("click", (e) => {
            if (e.target === eventModal) closeModal();
        });
    });
});
