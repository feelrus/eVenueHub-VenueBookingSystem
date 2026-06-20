function validateDates() {
    const startDate = document.getElementById("startDate").value;
    const startTime = document.getElementById("startTime").value;
    const endDate = document.getElementById("endDate").value;
    const endTime = document.getElementById("endTime").value;

    const startDateTime = new Date(startDate + "T" + startTime);
    const endDateTime = new Date(endDate + "T" + endTime);

    if (startDateTime >= endDateTime) {
      alert("End date and time must be after the start date and time.");
      return false;
    }
    return true;
  }