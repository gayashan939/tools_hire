# Shelton Tool-Hire: Testing Documentation

This document outlines the testing strategy, test cases, and validation procedures for the Shelton Tool-Hire prototype.

## 1. Test Plan
The testing objective is to ensure the reliability of the rental calculator, the security of the admin portal, and the correctness of the review moderation workflow.

### Testing Scope
- **Frontend**: Responsive layout, form validation, AJAX/JS calculator.
- **Backend**: SQL query security, session management, image handling.
- **Database**: Integrity of foreign keys and ENUM constraints.

---

## 2. Functional Test Cases

| ID | Test Case | Description | Expected Result | Status |
|---|---|---|---|---|
| FT-01 | Tool Search | Search for "Drill" in the search bar. | Display only tools with "Drill" in name or description. | [ ] |
| FT-02 | Category Filter | Click "Power Tools" in the sidebar. | Display only tools belonging to Power Tools category. | [ ] |
| FT-03 | Sort by Price | Sort catalogue by "Price: Low to High". | Tools ordered correctly by daily hire price. | [ ] |
| FT-04 | Rental Calculation | Enter 2 days and 3 hours for a tool. | Total cost = (2 * Daily Rate) + (3 * Hourly Rate). | [ ] |
| FT-05 | Invalid Dates | Enter an end date before the start date. | System displays an error or alert. | [ ] |
| FT-06 | Review Submission | Submit a review as a guest. | Review stored in DB with 'pending' status; not visible publicly. | [ ] |

---

## 3. Validation Tests

| ID | Test Case | Input | Expected Result | Status |
|---|---|---|---|---|
| VT-01 | Empty Tool Form | Submit "Add Tool" with blank name. | HTML5 validation prevents submission. | [ ] |
| VT-02 | Negative Prices | Enter -$10.00 in a price field. | Server-side validation or DB constraint blocks entry. | [ ] |
| VT-03 | Large Text | Paste 5000 characters into review comment. | Database handles or truncates without crashing. | [ ] |

---

## 4. Security Tests

| ID | Test Case | Procedure | Expected Result | Status |
|---|---|---|---|---|
| ST-01 | SQL Injection | Enter `' OR '1'='1` in the search bar. | System treats input as a literal string; no data leak. | [ ] |
| ST-02 | XSS Attack | Enter `<script>alert(1)</script>` in review comment. | Script tags are escaped and rendered as text. | [ ] |
| ST-03 | Auth Bypass | Attempt to access `admin/dashboard.php` without logging in. | Redirected to `admin/index.php`. | [ ] |
| ST-04 | Password Hashing | Check database for plain-text passwords. | All passwords stored as BCRYPT hashes. | [ ] |

---

## 5. User Acceptance Tests (UAT)

| ID | Persona | Scenario | Success Criteria |
|---|---|---|---|
| UA-01 | DIY Customer | Needs a mower for the weekend, checks cost before booking. | User calculates exact weekend cost in < 30 seconds. |
| UA-02 | Branch Manager | Needs to approve a positive customer review. | Manager finds review in dashboard and approves in 3 clicks. |
| UA-03 | Content Admin | Adds a new high-end ladder to the featured list. | Ladder appears on homepage hero section immediately. |

---

## 6. Test Results (Placeholder)
- **Total Tests**: 16
- **Passed**: 0
- **Failed**: 0
- **Completion Date**: [Date]
