<style>
    .mb-30{ margin-bottom: 30px;}
    .mb-50{ margin-bottom: 50px;}
</style>
<div class="container">
    
    <h1 class="mb-50">Option Greek Calculator</h1>
    
    <form id="og-calc-form" onSubmit="return false;"  action="">
        
        <div class="row mb-30">
            <div class="col-xl-4 col-12">
                <label for="input-spot" class="form-label">Spot</label>
                <input type="number" id="input-spot" placeholder="(Ex. 1000.0)" value="8823.25" step="0.01" class="form-control">
            </div>
            <div class="col-xl-4 col-12">
                <label for="input-strike" class="form-label">Strike</label>
                <input type="number" id="input-strike" placeholder="(Ex. 1000.0)" value="10000" step="0.01" class="form-control">
            </div>
            <div class="col-xl-4 col-12">
                <label for="datetimepicker" class="form-label">Expiry</label>
                <input type="datetime-local" id="datetimepicker" placeholder="(Ex. 2015-12-30 15:30:00)" value='<?php echo date("d/m/Y, H:i"); ?>' class="form-control">
            </div>
        </div>
        <div class="row mb-30">
            <div class="col-xl-4 col-12">
                <label for="input-volt" class="form-label">Volatility (%)</label>
                <input type="number" step="0.01" id="input-volt" placeholder="(Ex. 20)" value="59.33" class="form-control">
            </div>
            <div class="col-xl-4 col-12">
                <label for="input-intrate" class="form-label">Interest (%)</label>
                <input type="number" step="0.01" id="input-intrate" placeholder="(Ex. 3.7)" value="3.7" class="form-control">
            </div>
            <div class="col-xl-4 col-12">
                <label for="input-divyld" class="form-label">Dividend</label>
                <input type="number" id="input-divyld" placeholder="(Ex. 0.0)" value="0.0" class="form-control">
            </div>
        </div>

        <button type="submit" class="btn btn-primary mb-30" id="calc-button">Calculate</button>
    </form>
    
    
    <div id="results" style="display: inline;">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Call Option Premium</th>
                    <th>Put Option Premium</th>
                    <th>Call Option Delta</th>
                    <th>Put Option Delta</th>
                    <th>Option Gamma</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="results-value positive" id="call-option-prem-value"></td>
                    <td class="results-value positive" id="put-option-prem-value"></td>
                    <td class="results-value positive" id="call-option-delta-value"></td>
                    <td class="results-value negative" id="put-option-delta-value"></td>
                    <td class="results-value positive" id="option-gamma-value"></td>
                </tr>
            </tbody>
        </table>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Call Option Theta</th>
                    <th>Put Option Theta</th>
                    <th>Call Option Rho</th>
                    <th>Put Option Rho</th>
                    <th>Option Vega</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="results-value negative" id="call-option-theta-value"></td>
                    <td class="results-value negative" id="put-option-theta-value"></td>
                    <td class="results-value positive" id="class-option-rho-value"></td>
                    <td class="results-value negative" id="put-option-rho-value"></td>
                    <td class="results-value positive" id="option-vega-value"></td>
                </tr>
            </tbody>
        </table>
    </div>
    
</div>