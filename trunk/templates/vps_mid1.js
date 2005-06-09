|| document.PersonalInfo.pwdOldPassword.value == ""
				|| document.PersonalInfo.pwdPassword.value == "")
			{
				alert("Sorry, all personal info fields are required and must be filled out.");
				return;
			}

			if (document.PersonalInfo.pwdPassword.value == document.PersonalInfo.pwdPassword2.value)
				document.PersonalInfo.submit();
			else
				alert("Sorry, the two password fields don't match.  Please try again.");
		}
		
		function sendResponse(responseType, messageFrom, gameID)
		{
			document.responseToInvite.response.value = responseType;
			document.responseToInvite.messageFrom.value = messageFrom;
			document.responseToInvite.gameID.value = gameID;
			document.responseToInvite.submit();
		}

		function loadGame(gameID)
		{
			//if (document.existingGames.rdoShare[0].checked)
			//	document.existingGames.action = "opponentspassword.php";

			document.existingGames.gameID.value = gameID;
			document.existingGames.submit();
		}

		function withdrawRequest(gameID)
		{
			document.withdrawRequestForm.gameID.value = gameID;
			document.withdrawRequestForm.submit();
		}