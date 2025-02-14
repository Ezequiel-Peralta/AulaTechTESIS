/**
 *	Neon Calendar Script
 *
 *	Developed by Arlind Nushi - www.laborator.co
 */

var neonCalendar = neonCalendar || {};


function fit_calendar_container_height()
{
	if(neonCalendar.isPresent)
	{
		if(neonCalendar.$sidebar.height() < neonCalendar.$body.height())
		{
			neonCalendar.$sidebar.height( neonCalendar.$body.height() );
		}
		else
		{
			var old_height = neonCalendar.$sidebar.height();

			neonCalendar.$sidebar.height('');

			if(neonCalendar.$sidebar.height() < neonCalendar.$body.height())
			{
				neonCalendar.$sidebar.height(old_height);
			}
		}
	}
}

function reset_calendar_container_height()
{
	if(neonCalendar.isPresent)
	{
		neonCalendar.$sidebar.height('auto');
	}
}

function calendar_toggle_checkbox_status(checked)
{
	neonCalendar.$body.find('table tbody input[type="checkbox"]' + (checked ? '' : ':checked')).attr('checked',  ! checked).click();
}