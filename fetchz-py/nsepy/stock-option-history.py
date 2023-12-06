from datetime import date
from nsepy import get_history

# Stock options (Similarly for index options, set index = True)
stock_opt = get_history(symbol="AXISBANK",
                        start=date(2019,6,1),
                        end=date(2019,6,28),
                        option_type="CE",
                        strike_price=800,
                        expiry_date=date(2019,6,27))

print(stock_opt)