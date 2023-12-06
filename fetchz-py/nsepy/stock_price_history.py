from datetime import date
from nsepy import get_history
historyz = get_history(symbol='ESABINDIA',
                   start=date(2019,5,24),
                   end=date(2019,5,28));
print(historyz);
