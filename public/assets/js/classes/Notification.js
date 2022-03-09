/*************
 * Class notification
 *  Takes in a successState and message
 *  Adds the passed in message to the notification and customizes 
 *  the notification depending on whether successState is true or false.
 *  returnnotification() has to be called to retrieve the notification.
 *  initializenotification is then called to provide the fancy functionality
 *  to the notification.
 */
 class Notification {

    constructor(successState, message) {
        this.successState = successState;
        this.messageText = message;
        this.notification = this.createNotification();
        this.initializeNotification();
    }
    
    createNotification() {
        let notificationContainer = document.createElement("div");
        notificationContainer.classList.add("notification");
        notificationContainer.classList.add("ignore-overlay");

        let notificationIcon = document.createElement("i");

        if (this.successState == true) {
            notificationContainer.classList.add("notification-success");
            notificationIcon.classList.add("message-icon", "fas", "fa-check-circle");
        } else {
            notificationContainer.classList.add("notification-error");
            notificationIcon.classList.add("message-icon", "fas", "fa-exclamation-circle");
        }
        
        let messageContainer = document.createElement("div");
        messageContainer.classList.add("notification-message-container");


        let message = document.createElement("p");
        message.innerHTML = this.messageText;

        messageContainer.insertAdjacentElement("beforeend",notificationIcon);
        messageContainer.insertAdjacentElement("beforeend",message);

        let closeButtonContainer = document.createElement("div");
        closeButtonContainer.classList.add("close-notification-container");

        let closeIcon = document.createElement("i");
        closeIcon.classList.add("fas", "fa-times");
        
        closeButtonContainer.insertAdjacentElement("afterbegin", closeIcon);

        notificationContainer.insertAdjacentElement("beforeend", messageContainer);
        notificationContainer.insertAdjacentElement("beforeend", closeButtonContainer);
        return notificationContainer;
    }

    /**********
     * Logic for the notification
     * transforms it in on load
     * transforms out after delay then removes
     */
    initializeNotification() {
        if ($(".notification")) {
            $(".notification").remove();
        }
        let theNotification = this.notification;
        document.querySelector("body").insertAdjacentElement("afterbegin", theNotification);
        setTimeout(() => {
            theNotification.style.transform = "translateX(0%)";
            theNotification.style.marginRight = "10px";

        }, 50)

        let outTimeout = setTimeout(() => {
            theNotification.style.transform = "translateX(100%)";
            theNotification.style.marginRight = "0px";
            setTimeout(() =>{
                theNotification.remove();
            }, 4000)
        }, 5000);

        theNotification.addEventListener("mouseover", function() {
            clearTimeout(outTimeout);
            
        });

        theNotification.addEventListener("mouseleave", function() {
            setTimeout(() => {
                theNotification.style.transform = "translateX(100%)";
                theNotification.style.marginRight = "0px";
                setTimeout(() =>{
                    theNotification.remove();
                }, 4000);
            }, 5000);
        });
        
        theNotification.children[1].addEventListener("click", function() {
            theNotification.remove();
        })
        
    }
}