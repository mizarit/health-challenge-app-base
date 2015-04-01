function renderDate(date)
{
    if (typeof(local_data['steps']) == 'undefined') return;

    current_date = date;
    if ( typeof(local_data['steps'][current_date]) == 'undefined') {
        local_data['steps'][current_date] = 0;
        local_data['distance'][current_date] = 0;
        local_data['active_time'][current_date] = 0;
        local_data['cal_rest'][current_date] = 0;
        local_data['cal_active'][current_date] = 0;
        local_data['cal_total'][current_date] = 0;
    }
    $('page-title').innerHTML = local_data['date_names'][current_date];
    $('cell-steps').innerHTML = local_data['steps'][current_date];
    $('cell-distance').innerHTML = local_data['distance'][current_date];
    $('cell-longest-active-time').innerHTML = formatTime(local_data['longest_active_time'][current_date]);
    $('cell-active-time').innerHTML = formatTime(local_data['active_time'][current_date]);
    $('cell-inactive-time').innerHTML = formatTime(local_data['inactive_time'][current_date]);

    $('cell-cal-rest').innerHTML = formatSteps(Math.round(local_data['cal_rest'][current_date]))+'<span>cal.</span>';
    $('cell-cal-active').innerHTML = formatSteps(Math.round(local_data['cal_active'][current_date]))+'<span>cal.</span>';
    $('cell-cal-total').innerHTML = formatSteps(Math.round(local_data['cal_total'][current_date]))+'<span>cal.</span>';

    //$('group-users').innerHTML = local_data['group_users'] + ' actieve gebruikers';

    /*
     $('cell-group-steps').innerHTML = formatSteps(local_data['group_steps'][current_date]);
     $('cell-group-distance').innerHTML = local_data['distance'][current_date];
     $('cell-group-active-time').innerHTML = formatTime(local_data['group_active_time'][current_date]);

     $('cell-my-goal').innerHTML = formatSteps(local_data['my_goal']);
     $('cell-my-goal-perc').innerHTML = (local_data['steps_my'][current_date] > 0 ? local_data['steps_my'][current_date] / (local_data['my_goal'] / 100) : 0).toFixed(1) + '%';

     $('cell-group-goal').innerHTML = formatSteps(local_data['group_goal']);
     $('cell-group-goal-perc').innerHTML = (local_data['group_steps'][current_date] > 0 ? local_data['group_steps'][current_date] / (local_data['group_goal'] / 100) : 0).toFixed(1) + '%';

     $('cell-my-goal-week').innerHTML = formatSteps(local_data['my_goal'] * 7);
     $('cell-my-goal-week-perc').innerHTML = (local_data['week_steps'][current_date] > 0 ? local_data['week_steps'][current_date] / ((local_data['my_goal']*7) / 100) : 0).toFixed(1) + '%';

     $('cell-group-goal-week').innerHTML = formatSteps(local_data['group_goal'] * 7);
     $('cell-group-goal-week-perc').innerHTML = (local_data['group_week_steps'][current_date] > 0 ? local_data['group_week_steps'][current_date] / ((local_data['group_goal']*7) / 100) : 0).toFixed(1) + '%';
     */
    var vmax = 0;
    for ( i in local_data['data'][current_date]) {
        if(parseInt(local_data['data'][current_date][i]) > vmax) {
            vmax = local_data['data'][current_date][i];
        }
    }

    var factor = vmax > 0 ? ( 12 / vmax) : 0;

    for (k = 0; k < total_cols; k++) {
        kx = k;
        if (kx < 10) kx = '0' + kx;
        v = typeof(local_data['data'][current_date]) != 'undefined' && typeof(local_data['data'][current_date][kx]) != 'undefined' ? local_data['data'][current_date][kx] : 0;
        h = (v * factor)+0.1;
        $('graph-col-'+k).style.height = h + 'em';
    }
    /*
     var my_p_steps = local_data['group_steps'][current_date] > 0 ? (local_data['steps_my'][current_date] / (local_data['group_steps'][current_date] / 100)): 0;
     var my_p_distance = local_data['group_steps'][current_date] > 0 ? (local_data['distance_my'][current_date] / (local_data['group_distance'][current_date] / 100)): 0;
     var my_p_active_time = local_data['group_active_time'][current_date] > 0 ? (local_data['active_time_my'][current_date] / (local_data['group_active_time'][current_date] / 100)): 0;

     var avg_steps = local_data['group_steps'][current_date] / local_data['group_users'];
     var avg_distance = local_data['group_distance'][current_date] / local_data['group_users'];
     var avg_active_time = local_data['group_active_time'][current_date] / local_data['group_users'];

     var my_avg_steps = avg_steps > 0 ? (local_data['steps_my'][current_date] / (avg_steps / 100)) : 0;
     var my_avg_distance = avg_distance > 0 ? (local_data['distance_my'][current_date] / (avg_distance / 100)) : 0;
     var my_avg_active_time = avg_active_time > 0 ? (local_data['active_time_my'][current_date] / (avg_active_time / 100)) : 0;
     */
    /*
     $('cell-my-steps').innerHTML = my_p_steps.toFixed(1) + '%';
     $('cell-my-distance').innerHTML = my_p_distance.toFixed(1) + '%';
     $('cell-my-active-time').innerHTML = my_p_active_time.toFixed(1) + '%';

     $('cell-my-avg-steps').innerHTML = my_avg_steps.toFixed(1) + '%';
     $('cell-my-avg-distance').innerHTML = my_avg_distance.toFixed(1) + '%';
     $('cell-my-avg-active-time').innerHTML = my_avg_active_time.toFixed(1) + '%';
     */
}

function formatTime(seconds)
{
    h  = Math.floor(seconds / 3600);
    m = Math.floor((seconds - ( h * 3600)) / 60);
    if (h > 0) {
        return h+'<span>u</span> '+m+'<span>m</span>';
    }
    else {
        return m+'<span>m</span>'
    }
}

function formatSteps(steps)
{
    if (steps > 1000) {
        p1 = Math.floor(steps / 1000);
        p2 = steps - (p1*1000);
        return p1 + '.' + ("00"+p2).slice(-3);
    }
    return steps;
}

function formatDistance(distance)
{
    if (distance > 1000) {
        p3 = Math.floor(distance / 1000);
        p4 = distance - (p3*1000);
        p5 = p4.toFixed(2)-p4.toFixed();
        return p3 + '.' + ("0000"+p4.toFixed()).slice(-3)+','+(p5.toFixed(2).slice(-2));
    }
    return distance.toFixed(2).replace('.', ',');
}

function toggleSidebar(which)
{
    if (which =='sidebar-left' && $('sidebar-right').hasClassName('active')) {
        $('sidebar-right').removeClassName('active');
    }
    if (which =='sidebar-right' && $('sidebar-left').hasClassName('active')) {
        $('sidebar-left').removeClassName('active');
    }
    if ($(which).hasClassName('active')) {
        $(which).removeClassName('active');
    }
    else {
        $(which+'-inner').scrollTop = 0;
        $(which).addClassName('active');
    }

    if (which =='sidebar-left') {
        refreshChat(null, true);
        $('chat-count').style.display = 'none';
    }
}

function clearSidebars()
{
    //$('sidebar-left').removeClassName('active');
    //$('sidebar-right').removeClassName('active');
}

function redirect(uri) {
    if(navigator.userAgent.match(/Android/i))
        document.location=uri;
    else
        window.location.replace(uri);
}

Event.observe(window, 'load', function() {

    if (!iOS) {
        var swipeMain = $('body');
        var swipeMainObj = new Swipeable(swipeMain);

        var w = $('body').getWidth();

        // menu left
        swipeMain.observe("swipe:right", function () {
            p = swipeMainObj.lastStartX / (w / 100);

            if (p < 20) {
                if ($('sidebar-right').hasClassName('active')) {
                    toggleSidebar('sidebar-right');
                }
                else if (!$('sidebar-left').hasClassName('active')) {
                    toggleSidebar('sidebar-left');
                }
            }
        });

        var inChat = false;

        if ($('sidebar-left')) {
            var swipeSidebarLeft = $('sidebar-left');
            var swipeSidebarLeftObj = new Swipeable(swipeSidebarLeft);
            swipeSidebarLeft.observe("swipe:left", function () {
                p = swipeSidebarLeftObj.lastStartX / (w / 100);
                if (p > 80) {
                    if ($('sidebar-left').hasClassName('active')) {
                        if (!inChat) {
                            toggleSidebar('sidebar-left');
                        }
                        inChat = false;
                    }
                }
            });
        }
    }
    /*
     // menu right
     swipeMain.observe("swipe:left",function() {
     p = swipeMainObj.lastStartX / (w /100);

     //$('swipe-value').innerHTML = p;

     if (p > 80) {
     if( $('sidebar-left').hasClassName('active')) {
     toggleSidebar('sidebar-left');
     }
     else if ( !$('sidebar-right').hasClassName('active')) {
     toggleSidebar('sidebar-right');
     }
     }
     });


     if($('sidebar-right')) {
     var swipeSidebarRight = $('sidebar-right');
     var swipeSidebarRightObj = new Swipeable(swipeSidebarRight);
     swipeSidebarRight.observe("swipe:right", function () {
     p = swipeSidebarRightObj.lastStartX / (w / 100);
     if (p < 20) {
     if ($('sidebar-right').hasClassName('active')) {
     toggleSidebar('sidebar-right');
     }
     }
     });
     }
     */

    Event.observe($('chat-enter'), 'click', function() {
        inChat = true;
        sendChat();
    });
    Event.observe($('chat-enter'), 'touchstart', function() {
        inChat = true;
        sendChat();
    });



    if($('overlay')) {
        renderDataset();
    }

    setTimeout(function() {
        loadDataset();
    }, 30000);

    //toggleSidebar('sidebar-left');

    refreshChat();
});

function sendChat()
{
    if ($('chat-text').value != '') {
        refreshChat($('chat-text').value);
        $('chat-text').value = '';
    }
}

function refreshChat(new_chat, read)
{
    new Ajax.Request('/main/groupchat?ju='+jawbone_user_id, {
        parameters: {
            chat: new_chat,
            read: read
        },
        onSuccess: function(transport) {
            $('chat-stream').innerHTML = transport.responseJSON.html;
            if (transport.responseJSON.count > 0) {
                $('chat-count-value').innerHTML = transport.responseJSON.count;
                $('chat-count').style.display = 'block';
            }
            else {
                $('chat-count').style.display = 'none';
            }

        }
    });
    setTimeout(refreshChat, 15000);
}
function loadDataset()
{
    new Ajax.Request('/main/index?ju='+jawbone_user_id, {
        onSuccess: function(transport) {
            local_data = transport.responseJSON.local_data;
            current_date = transport.responseJSON.current_date;
            total_cols = transport.responseJSON.total_cols;

            if (transport.responseJSON.finished) {
                if(window.location.href.search(/index/i)!=-1) {
                    window.location.href = '/main/finish';
                }
            }

            renderDataset();
        }
    });

    setTimeout(function() {
        loadDataset();
    }, 30000);
}

function renderDataset()
{
    if (typeof(current_date)=='undefined') return;

    renderDate(current_date);

    $$('.graph-bar-flat').each(function(s,i) {
        $(s).removeClassName('graph-bar-flat');
    });

    $('page-prev').stopObserving();
    $('page-next').stopObserving();
    $('page-title').stopObserving();

    Event.observe($('page-prev'), 'click', function() {
        var now = new Date();

        var prev = new Date(current_date);
        prev.setDate(prev.getDate() - 1);

        if (prev > now) {
            $('page-next').addClassName('disabled');
        }
        else {
            $('page-next').removeClassName('disabled');
        }

        prev_d = prev.getDate();
        prev_m = prev.getMonth() + 1;
        prev_y = prev.getFullYear();
        prev = prev_y+'-'+(prev_m<10?'0'+prev_m:prev_m)+'-'+(prev_d<10?'0'+prev_d:prev_d);

        if(typeof(local_data['date_names'][prev]) != 'undefined') {
            renderDate(prev);
        }
    });

    Event.observe($('page-next'), 'click', function() {
        if ($('page-next').hasClassName('disabled')) {
            return;
        }
        var now = new Date();
        var next = new Date(current_date);
        var next2 = new Date(current_date);
        next.setDate(next.getDate() + 1);
        next2.setDate(next2.getDate() + 2);

        if (next2 > now) {
            $('page-next').addClassName('disabled');
        }
        else {
            $('page-next').removeClassName('disabled');
        }

        if(next > now) return;

        next_d = next.getDate();
        next_m = next.getMonth() + 1;
        next_y = next.getFullYear();
        next = next_y+'-'+(next_m<10?'0'+next_m:next_m)+'-'+(next_d<10?'0'+next_d:next_d);
        renderDate(next);
    });

    Event.observe($('page-title'), 'click', function() {
        var d = new Date;
        renderDate(d.getFullYear()+'-'+ ((d.getMonth()+1)<10?'0'+ (d.getMonth()+1): (d.getMonth()+1))+'-'+(d.getDate()<10?'0'+d.getDate():d.getDate()));
        $('page-next').addClassName('disabled');
    });

    // resize the racetrack
    teams = 0;
    for ( i in local_data['teams']) {
        teams++;
    }
    $('race-track-1').style.height = (teams * 4.5)+'em';
    $('checkerboard-1').style.height = (teams * 4.5)+'em';
    $('startline-1').style.width = (teams * 3)+'em';
    $('startline-1').style.left = (teams * -3)+'em';

    $('team-summary').innerHTML = '';
    $('personal-summary').innerHTML = '';

    for ( i in local_data['teams']) {
        $('race-track-inner-bar-'+i).style.width = local_data['teams'][i]['progress']+'%';
        $('race-track-label-'+i).innerHTML = local_data['teams'][i]['progress']+'%';

        $('race-track-inner-bar-'+i).style.background = '#'+local_data['teams'][i]['color'];
        var d = new Element('div', { class: 'cell-c1', id: 'team-summary-'+i });
        var d2  = new Element('div', { class: 'cell-a1' });
        var d3  = new Element('div', { class: 'legend', style: 'background: #'+local_data['teams'][i]['color'] });
        d2.insert('<span>'+formatDistance(local_data['teams'][i]['total']['distance'])+' km</span><br>'+formatSteps(local_data['teams'][i]['total']['steps'])+' stappen');
        d2.insert({top: d3});
        d.insert(d2);

        var d4 = new Element('div', { class: 'cell-b1' });
        var d5 = new Element('div', { class: 'open' });
        d4.insert('<span>'+formatDistance(local_data['teams'][i]['today']['distance'])+' km</span><br>'+formatSteps(local_data['teams'][i]['today']['steps'])+' stappen');
        d4.insert({top: d5});
        d.insert(d4);

        var d6 = new Element('div', { style: 'clear:both;'});
        $('team-summary').insert(d6);

        $('team-summary').insert(d);

        Event.observe(d, 'click', function() {
            goPage(3, this.id.substr(13));
        });
    }

    var d = new Element('div', { class: 'cell-c1', id: 'personal-summary-1' });
    var d2  = new Element('div', { class: 'cell-a1' });
    var d3  = new Element('div', { class: 'legend', style: 'background: #'+local_data['personal']['color'] });
    d2.insert('<span>'+formatSteps(local_data['personal']['total']['distance'])+' km</span><br>'+formatSteps(local_data['personal']['total']['steps'])+' stappen');
    d2.insert({top: d3});
    d.insert(d2);

    var d4 = new Element('div', { class: 'cell-b1' });
    var d5 = new Element('div', { class: 'open' });
    d4.insert('<span>'+formatSteps(local_data['personal']['today']['distance'])+' km</span><br>'+formatSteps(local_data['personal']['today']['steps'])+' stappen');
    d4.insert({top: d5});
    d.insert(d4);

    var d6 = new Element('div', { style: 'clear:both;'});
    $('personal-summary').insert(d6);

    $('personal-summary').insert(d);

    Event.observe(d, 'click', function() {
        goPage(2);
    });
}
function goPage(page_id, team_id)
{
    if (team_id > 0) {
        team = local_data['teams'][team_id];
        $('race-track-team-inner-bar-1').style.background = '#'+team.color;
        $('race-track-team-inner-bar-1').style.width = team.progress +  '%';
        $('race-track-team-label-1').innerHTML = team.progress +  '%';
        $('race-track-team-label-2').innerHTML = formatSteps(team.total.distance) +  ' km<br><span>'+formatSteps(team.total.steps)+' stappen</span>';

        $('ranking').innerHTML = '';

        for (i in team['ranking']) {
            var li = new Element('li');
            var d = new Element('div');
            li.insert(d);

            var d2 = new Element('div');
            var span = new Element('span');
            span.innerHTML = i;
            d2.insert(span);
            li.insert(d2);

            var img = new Element('img', { src: team['ranking'][i]['avatar']});
            d2.insert(img);

            d2.insert('<span>'+formatSteps(team['ranking'][i]['steps'])+'</span> stappen<br>'+team['ranking'][i]['title']);

            var d3 = new Element('div');
            li.insert(d3);

            $('ranking').insert(li);
        }
    }

    $('container').scrollTo();

    for(i=1;i<4;i++) {
        if (page_id == i) {
            $('page-'+i).addClassName('active');
        }
        else {
            $('page-'+i).removeClassName('active');
        }
    }

    if (page_id==1) {
        $('back-button').hide();
        if (iOS) {
            $('menu-button').show();
        }
        //$('subteaser1').show();
        //$('subteaser2').show();
    }
    else {
        $('back-button').show();
        if (iOS) {
            $('menu-button').hide();
        }
        //$('subteaser1').hide();
        //$('subteaser2').hide();
    }
}

function countStep() {
    $('android-step').style.display = 'block';
    setTimeout(function() { $('android-step').style.display = 'none'; }, 500);
}

function totalSteps(steps) {
    $('android-step-counter').innerHTML = steps;
}