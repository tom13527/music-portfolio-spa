/**
 * TO DO:
 *  - project fades in when loaded- need to make it consitent, though
 *      - check back with https://stackoverflow.com/questions/61349340/fading-in-html-code-dynamically-created-by-java-script-does-not-work-consistentl
 *  - spell check data
 *  - [Optional] try to add the description hamburger menu that opens and closes
 *      - maybe make it appear/slide in when hovering over the audio?
 *  - [Optional] add styling when mobile user taps the inactive buttons
 */

/**
 * This function will control what functions are triggered when the user clicks
 * on the buttons found on the html page
 */
const init = () => {

    // add styling when mobile user taps project buttons
    document.querySelectorAll(".project-button")
        .forEach(btn => btn .addEventListener("touchstart", mobileTapProjectButtons));
    document.querySelectorAll(".project-button")
        .forEach(btn => btn .addEventListener("touchend", mobileTapProjectButtonsEnd));
    document.querySelectorAll(".project-button")
        .forEach(btn => btn .addEventListener("touchmove", mobileTapProjectButtons));

    // This will deligate what project is loaded depending on the button clicked by the user
    document.querySelectorAll(".project-button")
        .forEach(btn => btn .addEventListener("click", loadProject, false));
}

/**
 * remove styling when mobile user taps project button
 * @param tapped the tapped button
 */
const mobileTapProjectButtons = tapped => {
    tapped.originalTarget.classList.add("project-button-tapped");

}

/**
 *  remove styling when user taps project button
 * @param tapped the tapped button 
 */
const mobileTapProjectButtonsEnd = tapped => {
    tapped.originalTarget.classList.remove("project-button-tapped");
}

/**
 * 
 * This function will create the html template of the page based on the button selected
 * by the user, as well as load the song information from the database.
 * @param projectName name attached to the project button that is clicked on
 */
const loadProject = projectName => {

    // send project name to function that will create page display
    createProjectDisplay(projectName);

    // Grab the songs of the project from the data base
    httpRequest("./php/selectSongs.php", "get", loadSongs, projectName.target.value, "XML");
}

/**
 * Using the song name sent from the button clicked, this fuction will create the html template of the page
 * @param projectName name attached to the project button that is clicked on
 */
createProjectDisplay = projectName => {

    // Add back arrow to header and change its color
    let header = document.querySelector("#header-text");
    header.innerHTML = "Back to Tom Good's Musical Projects &larr;";
    header.classList.add("back-button", "back-button");
    // Make header refresh button
    header.onclick = () => {
        location.reload();
    } 

    // clear the home page 
    let homePage = document.querySelector("#main");
    let homePageFooter = document.querySelector("#main-footer");
    document.body.removeChild(homePage);
    document.body.removeChild(homePageFooter)

    // Grab the data from the currently clicked on button
    let clickedProject = projectName.target.value;

    // create project title
    let projectTitleContainer = document.createElement("section");
    projectTitleContainer.setAttribute("id", "section-a");
    projectTitleContainer.setAttribute("class", "grid");
    let projectTitleContainerContentWrap = document.createElement("div");
    projectTitleContainerContentWrap.setAttribute("class", "content-wrap");
    let projectTitle = document.createElement("h1");
    projectTitle.innerHTML = clickedProject;
    let horizontalLine = document.createElement("hr");
    projectTitleContainerContentWrap.appendChild(projectTitle);
    projectTitleContainerContentWrap.appendChild(horizontalLine);
    projectTitleContainer.appendChild(projectTitleContainerContentWrap);

    // Create song container to be used later
    let songContainer = document.createElement("section");
    songContainer.setAttribute("id", "section-b");
    songContainer.setAttribute("class", "grid");
    let songContainerContentWrap = document.createElement("div");
    songContainerContentWrap.setAttribute("id", "song-container");
    songContainerContentWrap.setAttribute("class", "content-wrap");
    songContainer.appendChild(songContainerContentWrap);
    
    // make sure display does not already exist before creating it... just in case
    if(!document.querySelector("#main")) {

        // Create main tag to contain content
        let project = document.createElement("main");
        project.setAttribute("id", "main");

        // Add each category to project container
        project.appendChild(projectTitleContainer);
        project.appendChild(songContainer);
        document.body.appendChild(project);

        // fade in the elements
        setTimeout(fadeInElements(project), 100);
    }
    projectName.stopPropagation();

    // add styling when mobile user taps back button header
    document.querySelectorAll("#header-text")
        .forEach(btn => btn .addEventListener("touchstart", mobileTapHeader));
    document.querySelectorAll("#header-text")
        .forEach(btn => btn .addEventListener("touchend", mobileTapHeaderEnd));
    document.querySelectorAll("#header-text")
        .forEach(btn => btn .addEventListener("touchmove", mobileTapHeader));
}

/**
 * Add style when mobile user taps the header
 * @param tapped the tapped html element
 */
const mobileTapHeader = tapped => {
    tapped.originalTarget.classList.add("back-button-tapped");
}

/**
 * Remove styling when mobile user taps the header
 * @param tapped the tapped html element 
 */
const mobileTapHeaderEnd = tapped => {
    tapped.originalTarget.classList.remove("back-button-tapped");
}

/**
 * Fade in the html page containing the songs when it is loaded
 * @param project the entire project html main tag
 * @param footer the footer of the page
 */
const fadeInElements = (project, footer) => {
    project.classList.add("main-fade", "faded-out");

    requestAnimationFrame(() => {
        project.classList.remove("faded-out");
    })
}

/**
 *  Load the songs from the database tied with the specific project selected and then send them to be displayed dynamically
 *  @param projects the project that is called for
 */
const loadSongs = projects => {

    // Grab the data needed to make seperation more easy
    let projectNodes = projects.getElementsByTagName("project");
    
    // assign nodes to variables
    for(let i=0;i<projectNodes.length;i++) {
        let currentProject = projectNodes[i];
        let songId = currentProject.getElementsByTagName("SongId")[0].childNodes[0].nodeValue;
        let songName = currentProject.getElementsByTagName("Name")[0].childNodes[0].nodeValue;
        let projectName = currentProject.getElementsByTagName("ProjectName")[0].childNodes[0].nodeValue;
        let songDescription = currentProject.getElementsByTagName("Description")[0].childNodes[0].nodeValue;
        let filePath = currentProject.getElementsByTagName("FilePath")[0].childNodes[0].nodeValue;
        let projects = {"songId": songId, "name": songName, "projectName": projectName, "description": songDescription, "filePath": filePath};
        songsCollection =+ projects;
        createSongDisplay(projects);
    }
    // recreate footer
    let footer = document.createElement("footer");
    footer.setAttribute("id", "main-footer");
    footer.setAttribute("class", "grid");
    let footerText = document.createElement("div");
    footerText.innerHTML = "Website by Tom Good";
    footer.appendChild(footerText);
    document.body.appendChild(footer);

}

/**
 * Create the song display container as the songs are loaded from the database
 * 
 * @param projects the project now formatted 
 */
const createSongDisplay = projects => {


    // create the audio player
    let songAudioFileContainer = document.createElement("audio");
    songAudioFileContainer.controls = "controls";
    let songAudioFile = document.createElement("source");
    songAudioFile.setAttribute("src", projects.filePath);
    songAudioFile.setAttribute("type", "audio/mpeg");
    songAudioFile.setAttribute("class", "page-audio");
    songAudioFileContainer.appendChild(songAudioFile);

    // create song name
    let songName = document.createElement("h3");
    songName.innerHTML = `${projects.name}`;

    // create description text
    let descriptionText = document.createElement("p");
    descriptionText.setAttribute("class", "song-description");
    descriptionText.innerHTML = projects.description;

    // append all above to the exisiting container
    let songContainer = document.getElementById("song-container");
    songContainer.appendChild(songName);
    songContainer.appendChild(songAudioFileContainer);
    songContainer.appendChild(descriptionText);
}

/**
 * This function will be the structure for making the http request
 * @param url 
 * @param method 
 * @param callback 
 * @param params 
 * @param responseType 
 */
const httpRequest = (url, method, callback, params, responseType, ) => {
    let xhr = new XMLHttpRequest();
    if(params && method == "get") {
        url += "?" + params;
    }
    xhr.open(method, url);
    xhr.onreadystatechange = () => {
        if(xhr.readyState == 4) {
            if(responseType == "JSON") {
                callback(JSON.parse(xhr.requestText));
            } else if (responseType == "XML") {
                callback(xhr.responseXML);
            } else {
                callback(xhr.responseText)
            }
        }
    }
    if (method == "post") {
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send(params);
    } else {
        xhr.send(null);
    }
}

window.onload = init;