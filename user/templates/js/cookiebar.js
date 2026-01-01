/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 03 14
 * @since       PHPBoost 5.0 - 2016 09 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 */

document.addEventListener('DOMContentLoaded', () => {
    showCookieBar();
});

const showCookieBar = () => {
    let L_BUTTON = '';

    // Case 1: NO TRACKING - Only technical cookies (login / preferences / anonymous stats)
    // Case 2: TRACKING - Cookies for tracking systems (google-analytics, advertising, social networks links, and so on...)
    if (COOKIEBAR_TRACKING_MODE === 'notracking') {
        L_BUTTON = '<button type="button" class="button submit grouped-element" id="cookiebar-button-allowed">' + L_COOKIEBAR_UNDERSTAND + '</button>';
    } else {
        L_BUTTON = '<button type="submit" class="button bgc success grouped-element" id="cookiebar-button-allowed">' + L_COOKIEBAR_ALLOWED + '</button>' +
                    '<button type="submit" class="button bgc error grouped-element" id="cookiebar-button-declined">' + L_COOKIEBAR_DECLINED + '</button>';
    }

    if (getCustomCookie('pbt-cookiebar-viewed') === '') {
        // Add cookies bar if it doesn't exist
        if (document.querySelector('#cookiebar-container') === null) {
            document.body.insertAdjacentHTML('afterbegin', '<div class="cookiebar-container" id="cookiebar-container">' +
                                                            '<div class="cookiebar-content" id="cookiebar-content">' + L_COOKIEBAR_CONTENT + '</div>' +
                                                            '<div class="cookiebar-actions grouped-inputs">' + L_BUTTON +
                                                            '<a class="button small grouped-element offload cookiebar-question" href="' + U_COOKIEBAR_ABOUTCOOKIE + '" aria-label="' + L_COOKIEBAR_MORE + '">' +
                                                            '<i class="fa fa-question"></i></a></div></div>');
        }

        // If cookie is accepted then save this choice
        document.querySelector('#cookiebar-button-allowed').addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelector('#cookiebar-container').style.display = 'none';
            setCustomCookie('pbt-cookiebar-viewed', 1, COOKIEBAR_DURATION);
            setCustomCookie('pbt-cookiebar-choice', 1, COOKIEBAR_DURATION);
            showChangeChoice();
        });

        // If cookie is not accepted then save this choice
        document.querySelector('#cookiebar-button-declined').addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelector('#cookiebar-container').style.display = 'none';
            setCustomCookie('pbt-cookiebar-viewed', 1, COOKIEBAR_DURATION);
            setCustomCookie('pbt-cookiebar-choice', -1, COOKIEBAR_DURATION);
            showChangeChoice();
        });
    } else {
        showChangeChoice();
    }
};

const showChangeChoice = () => {
    const changeChoiceElement = document.querySelector('#cookiebar-change-choice');
    if (changeChoiceElement && getCustomCookie('pbt-cookiebar-viewed') === '1') {
        changeChoiceElement.insertAdjacentHTML('beforeend', '<button type="button" onclick="changeCookieBarChoice();" id="cookiebar-change-choice-link" class="cookiebar-change-choice-link">' + L_COOKIEBAR_CHANGE_CHOICE + '</button>');
    }
};

// Delete cookies from cookiebar.
const changeCookieBarChoice = () => {
    eraseCustomCookie('pbt-cookiebar-viewed');
    eraseCustomCookie('pbt-cookiebar-choice');
    document.querySelector('#cookiebar-change-choice-link').remove();
    showCookieBar();
};

// Custom Cookie functions
const setCustomCookie = (name, value, days) => {
    let expires = '';
    if (days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = '; expires=' + date.toUTCString();
    }
    document.cookie = name + '=' + (value || '') + expires + '; path=/; SameSite=Lax';
};

const getCustomCookie = (name) => {
    const nameEQ = name + '=';
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
};

const eraseCustomCookie = (name) => {
    document.cookie = name + '=; Max-Age=-99999999; path=/; SameSite=Lax';
};
