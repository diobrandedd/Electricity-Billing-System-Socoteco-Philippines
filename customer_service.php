<?php
$page_title = 'Customer Service - SOCOTECO II';
require_once 'includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0"><i class="fas fa-headset me-2"></i>Customer Service</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <p class="lead">We are committed to providing excellent customer service. Find all the information and resources you need here.</p>
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <h4 class="text-primary mb-3"><i class="fas fa-phone me-2"></i>Contact Information</h4>
                        </div>
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="text-primary">Main Office</h5>
                                            <p><i class="fas fa-map-marker-alt me-2"></i>Jose Catolico Avenue, Brgy Lagao<br>
                                            General Santos City, 9500</p>
                                            <p><i class="fas fa-phone me-2"></i>(083) 553-5848 to 50</p>
                                            <p><i class="fas fa-mobile-alt me-2"></i>09177205365 / 09124094971</p>
                                            <p><i class="fas fa-envelope me-2"></i>info@socoteco2.com</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="text-primary">Sub-Offices</h5>
                                            <div class="row">
                                                <div class="col-6">
                                                    <p><strong>Calumpang</strong><br>09639331803</p>
                                                    <p><strong>Polomolok</strong><br>09815059290</p>
                                                    <p><strong>Tupi</strong><br>09085663964</p>
                                                </div>
                                                <div class="col-6">
                                                    <p><strong>Alabel</strong><br>09977547974</p>
                                                    <p><strong>Malapatan</strong><br>09554488417</p>
                                                    <p><strong>Glan</strong><br>09752359732</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="text-primary">Emergency Hotline</h5>
                                    <div class="alert alert-danger">
                                        <h6><i class="fas fa-exclamation-triangle me-2"></i>24/7 Emergency</h6>
                                        <p class="mb-0">Call us anytime for power emergencies</p>
                                        <h4 class="text-danger">(083) 553-5848</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Operating Hours -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <h4 class="text-primary mb-3"><i class="fas fa-clock me-2"></i>Operating Hours (Teller)</h4>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Main Office</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Monday - Friday</strong></td>
                                            <td>8:00 AM - 5:00 PM</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Saturday</strong></td>
                                            <td>8:00 AM - 12:00 PM</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Sunday</strong></td>
                                            <td>Closed</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Sub-Offices</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Monday - Friday</strong></td>
                                            <td>8:00 AM - 4:00 PM</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Saturday</strong></td>
                                            <td>8:00 AM - 11:00 AM</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Sunday</strong></td>
                                            <td>Closed</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Service Applications -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <h4 class="text-primary mb-3"><i class="fas fa-search me-2"></i>Electric Service Application Status</h4>
                        </div>
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Check Your Application Status</h5>
                                    <form>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="application_number" class="form-label">Application Number</label>
                                                <input type="text" class="form-control" id="application_number" placeholder="Enter application number">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="customer_name" class="form-label">Customer Name</label>
                                                <input type="text" class="form-control" id="customer_name" placeholder="Enter customer name">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Check Status</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Application Requirements</h5>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success me-2"></i>Valid ID</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Proof of Ownership</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Barangay Clearance</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Application Form</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Connection Fee</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- SMS Broadcast System -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <h4 class="text-primary mb-3"><i class="fas fa-sms me-2"></i>Register to SMS Broadcast System</h4>
                        </div>
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Stay Updated via SMS</h5>
                                    <p>Receive important announcements, power interruption notices, and billing reminders via SMS.</p>
                                    <form>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="mobile_number" class="form-label">Mobile Number</label>
                                                <input type="tel" class="form-control" id="mobile_number" placeholder="09XXXXXXXXX">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="account_number" class="form-label">Account Number</label>
                                                <input type="text" class="form-control" id="account_number" placeholder="Enter account number">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="customer_name_sms" class="form-label">Customer Name</label>
                                            <input type="text" class="form-control" id="customer_name_sms" placeholder="Enter customer name">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Register for SMS</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">SMS Services</h5>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-bell text-primary me-2"></i>Power Interruption Alerts</li>
                                        <li><i class="fas fa-file-invoice text-primary me-2"></i>Billing Notifications</li>
                                        <li><i class="fas fa-exclamation-triangle text-primary me-2"></i>Emergency Announcements</li>
                                        <li><i class="fas fa-info-circle text-primary me-2"></i>General Updates</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Support Channels -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <h4 class="text-primary mb-3"><i class="fas fa-life-ring me-2"></i>Support Channels / Resources</h4>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card h-100 text-center">
                                <div class="card-body">
                                    <i class="fas fa-phone fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">Phone Support</h5>
                                    <p class="card-text">Call us for immediate assistance</p>
                                    <p class="fw-bold">(083) 553-5848</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card h-100 text-center">
                                <div class="card-body">
                                    <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">Email Support</h5>
                                    <p class="card-text">Send us your concerns</p>
                                    <p class="fw-bold">support@socoteco2.com</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card h-100 text-center">
                                <div class="card-body">
                                    <i class="fas fa-comments fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">Live Chat</h5>
                                    <p class="card-text">Chat with our support team</p>
                                    <button class="btn btn-primary">Start Chat</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card h-100 text-center">
                                <div class="card-body">
                                    <i class="fas fa-question-circle fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">FAQ</h5>
                                    <p class="card-text">Find answers to common questions</p>
                                    <a href="#" class="btn btn-primary">View FAQ</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Complaint Form -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <h4 class="text-primary mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Complaint Form</h4>
                        </div>
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Submit Your Complaint</h5>
                                    <form>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="complaint_name" class="form-label">Name</label>
                                                <input type="text" class="form-control" id="complaint_name" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="complaint_contact" class="form-label">Contact Number</label>
                                                <input type="tel" class="form-control" id="complaint_contact" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="complaint_account" class="form-label">Account Number</label>
                                            <input type="text" class="form-control" id="complaint_account">
                                        </div>
                                        <div class="mb-3">
                                            <label for="complaint_type" class="form-label">Complaint Type</label>
                                            <select class="form-select" id="complaint_type" required>
                                                <option value="">Select complaint type</option>
                                                <option value="billing">Billing Issue</option>
                                                <option value="service">Service Quality</option>
                                                <option value="outage">Power Outage</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="complaint_message" class="form-label">Message</label>
                                            <textarea class="form-control" id="complaint_message" rows="4" required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit Complaint</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Complaint Process</h5>
                                    <ol>
                                        <li>Submit your complaint</li>
                                        <li>Receive confirmation</li>
                                        <li>Investigation period (3-5 days)</li>
                                        <li>Resolution and feedback</li>
                                    </ol>
                                    <div class="alert alert-info">
                                        <small><i class="fas fa-info-circle me-2"></i>We aim to resolve complaints within 5 business days.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bill Calculator -->
                    <div class="row">
                        <div class="col-12">
                            <h4 class="text-primary mb-3"><i class="fas fa-calculator me-2"></i>Estimated Bill Calculator</h4>
                        </div>
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Calculate Your Estimated Bill</h5>
                                    <form id="billCalculator">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="consumption" class="form-label">Monthly Consumption (kWh)</label>
                                                <input type="number" class="form-control" id="consumption" placeholder="Enter kWh consumption">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="customer_type" class="form-label">Customer Type</label>
                                                <select class="form-select" id="customer_type">
                                                    <option value="residential">Residential</option>
                                                    <option value="commercial">Commercial</option>
                                                    <option value="industrial">Industrial</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" onclick="calculateBill()">Calculate Bill</button>
                                    </form>
                                    <div id="billResult" class="mt-3" style="display: none;">
                                        <div class="alert alert-success">
                                            <h6>Estimated Bill Breakdown:</h6>
                                            <div id="billBreakdown"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Current Rates</h5>
                                    <div class="rate-info">
                                        <h6>Residential</h6>
                                        <p class="mb-1">First 200 kWh: ₱7.84/kWh</p>
                                        <p class="mb-1">201-300 kWh: ₱8.50/kWh</p>
                                        <p class="mb-3">Above 300 kWh: ₱9.20/kWh</p>
                                        
                                        <h6>Commercial</h6>
                                        <p class="mb-1">All consumption: ₱8.20/kWh</p>
                                        
                                        <h6>Industrial</h6>
                                        <p class="mb-1">All consumption: ₱7.50/kWh</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function calculateBill() {
    const consumption = parseFloat(document.getElementById('consumption').value);
    const customerType = document.getElementById('customer_type').value;
    
    if (!consumption || consumption <= 0) {
        alert('Please enter a valid consumption amount');
        return;
    }
    
    let rate = 0;
    let breakdown = '';
    
    if (customerType === 'residential') {
        if (consumption <= 200) {
            rate = 7.84;
            breakdown = `First 200 kWh: ₱${rate.toFixed(2)}/kWh`;
        } else if (consumption <= 300) {
            rate = 8.50;
            breakdown = `201-300 kWh: ₱${rate.toFixed(2)}/kWh`;
        } else {
            rate = 9.20;
            breakdown = `Above 300 kWh: ₱${rate.toFixed(2)}/kWh`;
        }
    } else if (customerType === 'commercial') {
        rate = 8.20;
        breakdown = `All consumption: ₱${rate.toFixed(2)}/kWh`;
    } else if (customerType === 'industrial') {
        rate = 7.50;
        breakdown = `All consumption: ₱${rate.toFixed(2)}/kWh`;
    }
    
    const totalBill = consumption * rate;
    const vat = totalBill * 0.12;
    const finalBill = totalBill + vat;
    
    document.getElementById('billBreakdown').innerHTML = `
        <p><strong>Consumption:</strong> ${consumption} kWh</p>
        <p><strong>Rate:</strong> ${breakdown}</p>
        <p><strong>Subtotal:</strong> ₱${totalBill.toFixed(2)}</p>
        <p><strong>VAT (12%):</strong> ₱${vat.toFixed(2)}</p>
        <hr>
        <p><strong>Total Estimated Bill:</strong> ₱${finalBill.toFixed(2)}</p>
    `;
    
    document.getElementById('billResult').style.display = 'block';
}
</script>

<?php require_once 'includes/footer.php'; ?>
