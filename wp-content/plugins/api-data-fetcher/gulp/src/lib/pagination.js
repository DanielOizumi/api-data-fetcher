/**
 * Generates the pagination markup
 * @param {number} current - The current page number.
 * @param {number} total - The total number of pages.
 * @param {HTMLElement} template - The '<template>' element for pagination.
 * @returns {HTMLElement} - The pagination markup to append where you want.
 */

const gapAroundCurrent = 3;
const PAGE_CAP = 3 + gapAroundCurrent; // max number of pages before some numbers are hidden.

export default function generatePaginationMarkup(current, total, template) {
  current = Number(current);
  if (total <= 1) return document.createDocumentFragment();

  const pagination = template.content.cloneNode(true);
  const wrapper = pagination.querySelector(".u_pagination");
  wrapper.innerHTML = "";

  const prevPage = current === 1 ? current : current - 1;
  const nextPage = current === total ? total : current + 1;

  // Create the previous button
  const prev = createButton(
    "",
    prevPage,
    prevPage === current,
    ["page-prev", "page-target", "a-arrow", "is--left"],
    1 === current
  );
  prev.id = "page-prev";
  wrapper.appendChild(prev);

  // Calculate the pages to display before and after the current page
  const pagesToDisplayBeforeCurrent =
    current - gapAroundCurrent <= 1
      ? Math.max(current - 2, 0)
      : gapAroundCurrent;
  const pagesToDisplayAfterCurrent =
    current + gapAroundCurrent >= total - 1
      ? Math.max(total - current - 1, 0)
      : gapAroundCurrent;

  // Calculate additional pages to display to keep the total displayed pages at 11
  const additionalPagesToDisplayBeforeCurrent =
    Math.max(0, gapAroundCurrent - pagesToDisplayAfterCurrent) +
    (current === total ? 1 : 0);
  const additionalPagesToDisplayAfterCurrent =
    Math.max(0, gapAroundCurrent - pagesToDisplayBeforeCurrent) +
    (current === 1 ? 1 : 0);

  for (let page = 1; page <= total; page++) {
    let printed = false;
    if (
      page === 1 ||
      page === total ||
      page === current ||
      (page >=
        current -
          pagesToDisplayBeforeCurrent -
          additionalPagesToDisplayBeforeCurrent &&
        page < current) ||
      (page <=
        current +
          pagesToDisplayAfterCurrent +
          additionalPagesToDisplayAfterCurrent &&
        page > current)
    ) {
      // Create a page number button
      const number = createButton(
        page,
        page,
        page === current,
        ["page__number", "page-target"],
        false
      );
      wrapper.appendChild(number);
      printed = true;
    }
    if ((page === 2 || page === total - 1) && !printed) {
      wrapper.appendChild(document.createTextNode("..."));
    }
  }

  // Create the next button
  const next = createButton(
    "",
    nextPage,
    total === current,
    ["page-next", "page-target", "a-arrow"],
    total === current
  );
  next.id = "page-next";
  wrapper.appendChild(next);

  return pagination;
}

// Helper function to create a button element
const createButton = (text, page, isCurrent, classes, disabled = false) => {
  const button = document.createElement("button");
  button.classList.add(...classes);
  button.dataset.target = page;
  button.innerHTML = text;
  if (isCurrent) {
    button.classList.add("active");
    button.disabled = true;
  }
  button.disabled = disabled;
  return button;
};
