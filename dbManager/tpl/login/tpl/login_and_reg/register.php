<form action="" style="height: 100%;">
    <div role="group" id="regForm">
        <input id="newUserName" placeholder = "הכנס שם משתמש.." required>
<!--        <div class="markWrapper" id="wu-mark"><svg id="userVmark" class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/><path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/></svg></div>
-->        <input type="text" id="newUserEmail" placeholder = "הכנס אימייל.." required>
<!--        <div class="markWrapper" id="we-mark"><svg id="emailVmark" class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52"><circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/><path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/></svg></div>
-->        <input type="password" id="newUserPass" placeholder = "הכנס סיסמה.." required>
        <input type="button" id="registerButton" value="הרשם">
    </div>
    <style>
        .regErrors{

        }
    </style>
    <div class="regErrors jsMessage">
        <div class="regError" style="text-align: center;"></div>
    </div>
</form>