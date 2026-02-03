function wlAddOneYear(dateStr) {
  if (!dateStr) return "";
  const d = new Date(dateStr + "T00:00:00");
  if (Number.isNaN(d.getTime())) return "";
  d.setFullYear(d.getFullYear() + 1);
  const yyyy = d.getFullYear();
  const mm = String(d.getMonth() + 1).padStart(2, "0");
  const dd = String(d.getDate()).padStart(2, "0");
  return `${yyyy}-${mm}-${dd}`;
}

document.addEventListener("DOMContentLoaded", () => {
  // Auto-calc expiry date (issue_date + 1 year)
  const issue = document.querySelector("[data-wl-issue-date]");
  const expiry = document.querySelector("[data-wl-expiry-date]");
  if (issue && expiry) {
    const sync = () => {
      const v = issue.value;
      expiry.value = wlAddOneYear(v);
    };
    issue.addEventListener("change", sync);
    issue.addEventListener("keyup", sync);
    sync();
  }

  // Simple table filter
  document.querySelectorAll("[data-wl-table-filter]").forEach((input) => {
    const tableId = input.getAttribute("data-wl-table-filter");
    const table = document.getElementById(tableId);
    if (!table) return;
    input.addEventListener("input", () => {
      const q = input.value.trim().toLowerCase();
      table.querySelectorAll("tbody tr").forEach((tr) => {
        const text = tr.innerText.toLowerCase();
        tr.style.display = text.includes(q) ? "" : "none";
      });
    });
  });
});

